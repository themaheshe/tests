<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->prefix('api')->group(function () {
    Route::apiResource('clients', ClientController::class);
});
