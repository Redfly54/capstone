<?php

use App\Http\Controllers\AdopsiPetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;

Route::group(['prefix' => 'v1'], function () {

    Route::middleware('auth:sanctum')->get('/users/profile', [UserController::class, 'profile']);
    Route::middleware('auth:sanctum')->put('/users/changeprof', [UserController::class, 'updateDescription']);
    Route::middleware('auth:sanctum')->post('users/update-picture', [UserController::class, 'updatePicture']);
    Route::post('users/register', [UserController::class, 'register']);
    Route::post('users/login', [UserController::class, 'login']);
    Route::apiResource('users', UserController::class);


});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::post('adopsi-pets', [AdopsiPetController::class, 'store']);
Route::get('/pets', [AdopsiPetController::class, 'getAllPets']);
Route::get('/pet/details', [AdopsiPetController::class, 'getPetDetails']);
Route::post('/pet/delete', [AdopsiPetController::class, 'deletePet']);
Route::post('/pet/{id}', [AdopsiPetController::class, 'updatePet']);

