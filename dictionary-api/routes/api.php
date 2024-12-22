<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;


Route::get('/', [HomeController::class, 'index']);

// Autenticação
Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('signin', [AuthController::class, 'signin']);
});

Route::middleware('auth:api')->group(function () {
    // Rotas relacionadas às palavras (WordController)
    Route::get('/entries/en', [WordController::class, 'getDictionaryEntries']);
    Route::get('/entries/en/{word}', [WordController::class, 'getWordDetails']);
    Route::post('/entries/en/{word}/favorite', [WordController::class, 'addFavorite']);
    Route::delete('/entries/en/{word}/unfavorite', [WordController::class, 'removeFavorite']);

    // Rotas relacionadas ao histórico (HistoryController)
    Route::get('/user/me/history', [HistoryController::class, 'getHistory']);
    Route::post('/entries/en/{word}', [HistoryController::class, 'addHistory']);

    // Rotas relacionadas ao usuário (UserController)
    Route::get('/user/me', [UserController::class, 'me']);
    Route::get('/user/me/favorites', [FavoriteController::class, 'getFavorites']);
});