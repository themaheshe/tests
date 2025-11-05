<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class ClientApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        // Optionally set up seeding or anything needed
    }

    public function test_index_returns_limited_fields()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@example.com',
            'age' => 30,
            'linkedInUrl' => 'https://linkedin.com/in/testuser',
        ]);

        $response = $this->actingAs($user)->getJson('/api/clients');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $client->id,
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'testuser@example.com',
                'age' => 30,
                'linkedInUrl' => 'https://linkedin.com/in/testuser',
            ])
            ->assertJsonMissing(['user_id', 'created_at', 'updated_at']);
    }

    public function test_show_returns_limited_fields()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'Show',
            'last_name' => 'Person',
            'email' => 'showperson@example.com',
            'age' => 28,
            'linkedInUrl' => 'https://linkedin.com/in/showperson',
        ]);
        $response = $this->actingAs($user)->getJson("/api/clients/{$client->id}");
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $client->id,
                'first_name' => 'Show',
                'last_name' => 'Person',
                'email' => 'showperson@example.com',
                'age' => 28,
                'linkedInUrl' => 'https://linkedin.com/in/showperson',
            ])
            ->assertJsonMissing(['user_id', 'created_at', 'updated_at']);
    }

    public function test_update_returns_limited_fields()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'Old',
            'last_name' => 'Name',
            'email' => 'oldname@example.com',
            'age' => 45,
            'linkedInUrl' => 'https://linkedin.com/in/oldname',
        ]);
        $response = $this->actingAs($user)->putJson("/api/clients/{$client->id}", [
            'first_name' => 'New',
            'age' => 40
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $client->id,
                'first_name' => 'New',
                'last_name' => 'Name',
                'email' => 'oldname@example.com',
                'age' => 40,
                'linkedInUrl' => 'https://linkedin.com/in/oldname',
            ])
            ->assertJsonMissing(['user_id', 'created_at', 'updated_at']);
    }
}
