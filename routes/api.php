<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;

Route::get('/images/{filename}', function ($filename) {
    $path = storage_path('app/public/images/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
});

// Users Routes
Route::post('users/register', [UserController::class, 'register']);
Route::post('users/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->get('/users/profile', [UserController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/users/changeprof', [UserController::class, 'updateDescription']);
Route::middleware('auth:sanctum')->post('users/update-picture', [UserController::class, 'updatePicture']);
Route::apiResource('users', UserController::class);


// Data Routes
Route::get('/pet-categories', [DataController::class, 'getPetCategories']);
Route::get('/breeds', [DataController::class, 'getBreeds']);
Route::get('/ages', [DataController::class, 'getAges']);

// Posts Routes
Route::post('posts/create', [PostController::class, 'store']);
Route::get('/pets', [PostController::class, 'getAllPets']);
Route::get('/pet/details', [PostController::class, 'getPetDetails']);
Route::post('/pet/delete', [PostController::class, 'deletePet']);
Route::post('/pet/{id}', [PostController::class, 'updatePet']);

