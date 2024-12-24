<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;


class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api",
     *     tags={"Home"},
     *     summary="Verificar disponibilidade da API",
     *     description="Retorna uma mensagem simples para verificar se a API está funcionando.",
     *     @OA\Response(
     *         response=200,
     *         description="API disponível",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Fullstack Challenge 🏅 - Dictionary")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Fullstack Challenge 🏅 - Dictionary']);
    }
}
