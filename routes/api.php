<?php

use App\Http\Controllers\AdopsiPetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::apiResource('users', App\Http\Controllers\Api\v1\UserController::class);
// });

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('users', UserController::class);
    Route::post('users/login', [UserController::class, 'login']);
    // Route::apiResource('auth', AuthController::class)->only(['login', 'logout']);

    // Route::apiResource('posts', PostController::class);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::post('adopsi-pets', [AdopsiPetController::class, 'store']);