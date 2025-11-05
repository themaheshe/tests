<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clients', ClientController::class);
});