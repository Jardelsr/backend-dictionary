<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Conexão estabelecida com sucesso!";
    } catch (\Exception $e) {
        return "Erro ao conectar: " . $e->getMessage();
    }
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
