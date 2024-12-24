<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/signup",
     *     tags={"Auth"},
     *     summary="Registrar um novo usuário",
     *     description="Permite o registro de um novo usuário na aplicação.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="User 1"),
     *             @OA\Property(property="email", type="string", example="example@email.com"),
     *             @OA\Property(property="password", type="string", example="test1234")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", example="f3a10cec013ab2c1380acef"),
     *             @OA\Property(property="name", type="string", example="User 1"),
     *             @OA\Property(property="token", type="string", example="Bearer JWT.Token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object", additionalProperties={"type": "string"})
     *         )
     *     )
     * )
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }
        
        $user = User::create($request->only('name', 'email', 'password'));

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'id' => $user->_id,
            'name' => $user->name,
            'token' => 'Bearer ' . $token,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/signin",
     *     tags={"Auth"},
     *     summary="Autenticar um usuário",
     *     description="Permite que um usuário existente faça login.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="example@email.com"),
     *             @OA\Property(property="password", type="string", example="test1234")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", example="f3a10cec013ab2c1380acef"),
     *             @OA\Property(property="name", type="string", example="User 1"),
     *             @OA\Property(property="token", type="string", example="Bearer JWT.Token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Credenciais inválidas")
     *         )
     *     )
     * )
     */
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
