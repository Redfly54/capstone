<?php

use App\Http\Controllers\AdopsiPetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('users', UserController::class);
    Route::post('users/register', [UserController::class, 'register']);
    Route::post('users/login', [UserController::class, 'login']);

});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::post('adopsi-pets', [AdopsiPetController::class, 'store']);