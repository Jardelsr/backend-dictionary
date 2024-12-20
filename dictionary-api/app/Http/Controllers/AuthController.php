<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Registro de novo usuário
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($request->only('name', 'email', 'password'));

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'id' => $user->_id,
            'name' => $user->name,
            'token' => 'Bearer ' . $token,
        ], 201);
    }

    // Login do usuário
    public function signin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();

        return response()->json([
            'id' => $user->_id,
            'name' => $user->name,
            'token' => 'Bearer ' . $token,
        ], 200);
    }
}
