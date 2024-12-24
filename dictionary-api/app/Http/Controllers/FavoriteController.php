<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/me/favorites",
     *     tags={"Favorites"},
     *     summary="Listar palavras favoritas",
     *     description="Retorna uma lista de palavras que o usuário marcou como favoritas com paginação.",
     *     security={{"bearerAuth":{}}}, 
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
     *         description="Número da página para paginação",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de palavras favoritas retornada com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="results", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="word", type="string", example="example"),
     *                     @OA\Property(property="added", type="string", format="date-time", example="2024-12-23T12:34:56+00:00")
     *                 )
     *             ),
     *             @OA\Property(property="totalDocs", type="integer", example=50),
     *             @OA\Property(property="page", type="integer", example=1),
     *             @OA\Property(property="totalPages", type="integer", example=5),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
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
    public function getFavorites(Request $request)
    {
        $user = Auth::user();

        // Parâmetros de paginação
        $limit = $request->input('limit', 10); 
        $page = $request->input('page', 1);    

        $query = Favorite::where('user_id', $user->id)->orderBy('created_at', 'desc');

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
