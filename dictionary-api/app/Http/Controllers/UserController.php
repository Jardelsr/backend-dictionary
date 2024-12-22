<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    // Perfil do usuário
    public function me()
    {
        return response()->json(auth()->user(), 200);
    }

    // Listar palavras favoritas
    public function getFavorites()
    {
        $user = Auth::user();

        $favorites = $user->favorites()->orderBy('added_at', 'desc')->get();

        return response()->json(['results' => $favorites], 200);
    }
}
