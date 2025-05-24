<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MLController;

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
Route::middleware('auth:sanctum')->get('/users/favorites', [UserController::class, 'getFavorites']);
Route::middleware('auth:sanctum')->post('/users/favorites', [UserController::class, 'addFavorites']);
Route::middleware('auth:sanctum')->delete('/users/favorites/{id}', [UserController::class, 'removeFavorite']);
Route::apiResource('users', UserController::class);

// ML Routes
Route::get('/results/{user_id}', [MLController::class, 'getResult']);
Route::post('/recommendations/{user_id}', [MLController::class, 'recommend']);

// Data Routes
Route::get('/pet-categories', [DataController::class, 'getPetCategories']);
Route::post('/pet-categories', [DataController::class, 'addPetCategory']);
Route::put('/pet-categories/{id}', [DataController::class, 'editPetCategory']);
Route::delete('/pet-categories/{id}', [DataController::class, 'deletePetCategory']);
Route::get('/breeds', [DataController::class, 'getBreeds']);
Route::post('/breeds', [DataController::class, 'addBreed']);
Route::put('/breeds/{id}', [DataController::class, 'editBreed']);
Route::delete('/breeds/{id}', [DataController::class, 'deleteBreed']);
Route::get('/ages', [DataController::class, 'getAges']);

// Posts Routes
Route::post('posts/create', [PostController::class, 'store']);
Route::get('/pets', [PostController::class, 'getAllPets']);
Route::get('/pet/details/{id}', [PostController::class, 'getPetDetails']);
Route::post('/pet/delete/{id}', [PostController::class, 'deletePet']);
Route::post('/pet/{id}', [PostController::class, 'updatePet']);

