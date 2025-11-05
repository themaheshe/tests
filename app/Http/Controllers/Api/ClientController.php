<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Mail\ClientCreated;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationProvider;
use App\Http\Resources\ClientResource;
use App\Models\UserLog;

class ClientController extends Controller
{
    private $notificationProvider;

    public function __construct(NotificationProvider $notificationProvider)
    {
        // this uses SlackService atm but we can change it to any other service if we need in future.
        $this->notificationProvider = $notificationProvider;
    }

    public function index()
    {
        $user = Auth::user();
        $clients = Client::where('user_id', $user->id)->get();

        // only return the data that is needed for the client resource
        return ClientResource::collection($clients);
    }

    public function store(StoreClientRequest $request)
    {
        $user = Auth::user();

        return DB::transaction(function () use ($request, $user) {
            $client = Client::create(array_merge($request->validated(), [
                'user_id' => $user->id
            ]));

            UserLog::create([
                'action' => 'client_created',
                'user_id' => $user->id,
                'date_created' => now(),
            ]);

            Mail::to($user->email)->send(new ClientCreated());

            $this->notificationProvider->sendNotification('A client record was created by user ' . $user->id);
            return response()->json($client, 201);
        });
    }

    public function show($id)
    {
        $user = Auth::user();
        $client = Client::findOrFail($id);
        $this->authorize('view', $client);
        return new ClientResource($client);
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $user = Auth::user();
        $client = Client::findOrFail($id);
        $this->authorize('update', $client);
        
        return DB::transaction(function () use ($request, $client, $user) {
            $client->update($request->validated());
            UserLog::create([
                'action' => 'client_updated',
                'user_id' => $user->id,
                'date_created' => now(),
            ]);
            return new ClientResource($client);
        });
    }


    public function destroy($id)
    {
        // It is better to use softDelete but for test purpose this model does not use soft delete atm.
        $user = Auth::user();
        $client = Client::findOrFail($id);
        $this->authorize('delete', $client);
        
        return DB::transaction(function () use ($client, $user) {
            $client->delete();
            UserLog::create([
                'action' => 'client_deleted',
                'user_id' => $user->id,
                'date_created' => now(),
            ]);
            return response()->json(['message' => 'Client deleted.']);
        });
    }
}
