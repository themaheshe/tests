<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\UserLog;
use App\Services\NotificationProvider;
use Mockery\MockInterface;

class ClientApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_create_and_logs_inserted()
    {
        $user = User::factory()->create();
        $payload = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'age' => 29,
            'linkedInUrl' => 'https://linkedin.com/in/janesmith',
        ];

        // Mock the NotificationProvider (SlackService), we don't want to call the actual API.
        $this->mock(NotificationProvider::class, function (MockInterface $mock) {
            $mock->expects('sendNotification')->once();
        });

        $response = $this->actingAs($user)->postJson('/api/clients', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('clients', [
            'email' => 'jane@example.com',
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('user_logs', [
            'action' => 'client_created',
            'user_id' => $user->id,
        ]);
    }

    public function test_update_and_logs_inserted()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id, 'first_name' => 'OldName']);
        $payload = [ 'first_name' => 'NewName' ];
        $response = $this->actingAs($user)->putJson("/api/clients/{$client->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonFragment(['first_name' => 'NewName']);
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'first_name' => 'NewName',
        ]);
        $this->assertDatabaseHas('user_logs', [
            'action' => 'client_updated',
            'user_id' => $user->id,
        ]);
    }

    public function test_delete_and_logs_inserted()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->deleteJson("/api/clients/{$client->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Client deleted.']);
        $this->assertDatabaseMissing('clients', [ 'id' => $client->id ]);
        $this->assertDatabaseHas('user_logs', [
            'action' => 'client_deleted',
            'user_id' => $user->id,
        ]);
    }

    public function test_only_owner_can_update()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $owner->id, 'first_name' => 'AAA']);
        $this->actingAs($other)->putJson("/api/clients/{$client->id}", ['first_name' => 'HACK'])
            ->assertStatus(403);
        $this->assertDatabaseMissing('clients', [ 'id' => $client->id, 'first_name' => 'HACK' ]);
    }

    public function test_only_owner_can_delete()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $owner->id]);
        $this->actingAs($other)->deleteJson("/api/clients/{$client->id}")
            ->assertStatus(403);
        $this->assertDatabaseHas('clients', [ 'id' => $client->id ]);
    }

    public function test_only_owner_can_update_passes_for_owner()
    {
        $owner = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $owner->id, 'first_name' => 'BBBB']);
        $response = $this->actingAs($owner)->putJson("/api/clients/{$client->id}", ['first_name' => 'CCC']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [ 'id' => $client->id, 'first_name' => 'CCC' ]);
    }
    public function test_only_owner_can_delete_passes_for_owner()
    {
        $owner = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $owner->id]);
        $response = $this->actingAs($owner)->deleteJson("/api/clients/{$client->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('clients', [ 'id' => $client->id ]);
    }
}
