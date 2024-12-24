<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/me/history",
     *     tags={"History"},
     *     summary="Listar histórico de palavras do usuário",
     *     description="Retorna o histórico de palavras pesquisadas pelo usuário com suporte a paginação.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Histórico de palavras do usuário retornado com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="results", type="array", 
     *                 @OA\Items(type="object", 
     *                     @OA\Property(property="word", type="string", example="example"),
     *                     @OA\Property(property="added", type="string", format="date-time", example="2024-12-23T12:34:56+00:00")
     *                 )
     *             ),
     *             @OA\Property(property="totalDocs", type="integer", example=100),
     *             @OA\Property(property="page", type="integer", example=1),
     *             @OA\Property(property="totalPages", type="integer", example=10),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de solicitação, parâmetros inválidos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Parâmetros inválidos.")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número de resultados por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página para a paginação",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     )
     * )
     */
    public function getHistory(Request $request)
    {
        $user = Auth::user();

        // Parâmetros de paginação
        $limit = $request->input('limit', 10); 
        $page = $request->input('page', 1);    

        $query = History::where('user_id', $user->id)->orderBy('created_at', 'desc');

        $totalDocs = $query->count();

        $results = $query
        ->skip(($page - 1) * $limit)
        ->take($limit)
        ->get()
        ->map(function ($history) {
            return [
                'word' => $history->word,
                'added' => $history->created_at->toIso8601String(),
            ];
        });

        $totalPages = ceil($totalDocs / $limit);

        $hasNext = $page < $totalPages;
        $hasPrev = $page > 1;

        return response()->json([
            'results' => $results,
            'totalDocs' => $totalDocs,
            'page' => (int) $page,
            'totalPages' => $totalPages,
            'hasNext' => $hasNext,
            'hasPrev' => $hasPrev,
        ]);
    }
}
