<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/me",
     *     tags={"User"},
     *     summary="Perfil do usuário",
     *     description="Retorna as informações do usuário autenticado.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informações do usuário retornadas com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T12:34:56+00:00"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T12:34:56+00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuário não autenticado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuário não autenticado.")
     *         )
     *     )
     * )
     */
    public function me()
    {
        return response()->json(auth()->user(), 200);
    }
}
