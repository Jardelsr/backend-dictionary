<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Middleware\CacheResponse;

Route::get('/', [HomeController::class, 'index']);

Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']); // Registro de usuários
    Route::post('signin', [AuthController::class, 'signin']); // Login de usuários
});

Route::middleware('auth:api')->group(function () {
    Route::middleware([CacheResponse::class])->group(function () {
        Route::get('/entries/en', [WordController::class, 'getDictionaryEntries']); // Listar entradas no dicionário (com cache)
        Route::get('/entries/en/{word}', [WordController::class, 'getWordDetails']); // Detalhes de uma palavra (com cache)
    });

    Route::post('/entries/en/{word}/favorite', [WordController::class, 'addFavorite']); 
    Route::delete('/entries/en/{word}/unfavorite', [WordController::class, 'removeFavorite']); 

    Route::get('/user/me', [UserController::class, 'me']); 
    Route::get('/user/me/favorites', [FavoriteController::class, 'getFavorites']); 
    Route::get('/user/me/history', [HistoryController::class, 'getHistory']); 
});
