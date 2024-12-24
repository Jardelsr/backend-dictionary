<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\History;
use App\Models\Favorite;

class WordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/entries/en/{word}/favorite",
     *     tags={"Word"},
     *     summary="Adicionar palavra aos favoritos",
     *     description="Adiciona uma palavra à lista de favoritos de um usuário.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", example="example")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Palavra adicionada aos favoritos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Palavra adicionada aos favoritos."),
     *             @OA\Property(property="word", type="string", example="example")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="A palavra já está nos favoritos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Essa palavra já está nos favoritos.")
     *         )
     *     )
     * )
     */
    public function addFavorite(Request $request, $word)
    {
        $existingFavorite = Favorite::where('user_id', Auth::id())
            ->where('word', $word)
            ->first();
    
        if ($existingFavorite) {
            return response()->json([
                'message' => 'Essa palavra ja está nos favoritos.'
            ], 400);
        }

        $user = Auth::user();

        Favorite::create([
            'user_id' => $user->id,
            'word' => $word
        ]);
 
        return response()->json([
            'message' => 'Palavra adicionada aos favoritos.',
            'word' => $word
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/entries/en/{word}/unfavorite",
     *     tags={"Word"},
     *     summary="Remover palavra dos favoritos",
     *     description="Remove uma palavra da lista de favoritos de um usuário.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", example="example")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Palavra removida dos favoritos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="A palavra foi removida dos favoritos.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Palavra não encontrada nos favoritos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="A palavra não está nos favoritos.")
     *         )
     *     )
     * )
     */
    public function removeFavorite(Request $request, $word)
    {
        try {
            $user = Auth::user();

            $favorite = $user->favorites()->where('word', $word)->first();

            if (!$favorite) {
                return response()->json([
                    'message' => "A palavra não esta nos favoritos."
                ], 404);
            }

            // Remover a palavra dos favoritos
            $favorite->delete();

            // Retornar resposta de sucesso
            return response()->json([
                'message' => "A palavra foi removida dos favoritos."
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Um erro ocorreu ao tentar remover a palavra dos favoritos.'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/entries/en/{word}",
     *     tags={"Word"},
     *     summary="Obter detalhes de uma palavra",
     *     description="Retorna os detalhes de uma palavra a partir de uma API externa.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", example="example")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da palavra obtidos com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"word": "example", "definition": "A representative form of a concept."}
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Palavra não encontrada.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Palavra não encontrada ou erro interno da API.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro durante o processo de busca da palavra.")
     *         )
     *     )
     * )
     */
    public function getWordDetails($word)
    {
        try {
            $response = Http::withoutVerifying()->get("https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");
    
            if ($response->failed()) {
                return response()->json(['message' => 'Palavra não encontrada ou erro interno da API.'], 404);
            }
    
            $wordData = $response->json();
    
            $user = Auth::user();
    
            $existingHistory = History::where('user_id', $user->id)
                ->where('word', $word)
                ->first();
    
            if (!$existingHistory) {
                History::create([
                    'user_id' => $user->id,
                    'word' => $word
                ]);
            }
    
            return response()->json($wordData, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro durante o processo de busca da palavra.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/entries/en",
     *     tags={"Word"},
     *     summary="Listar palavras do dicionário",
     *     description="Retorna uma lista de palavras com suporte a paginação e busca.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Termo de busca",
     *         @OA\Schema(type="string", example="example")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limite de palavras por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de palavras com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="results", type="array", @OA\Items(type="string", example="example")),
     *             @OA\Property(property="totalDocs", type="integer", example=100),
     *             @OA\Property(property="page", type="integer", example=1),
     *             @OA\Property(property="totalPages", type="integer", example=10),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function getDictionaryEntries(Request $request)
    {
        $search = $request->query('search', '');
        $limit = (int) $request->query('limit', 10);
        $page = (int) $request->query('page', 1);

        $query = Word::query();

        if (!empty($search)) {
            $query->where('word', 'like', "{$search}%");
        }

        $totalDocs = $query->count();
        $words = $query->orderBy('word', 'asc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $totalPages = (int) ceil($totalDocs / $limit);
        $hasNext = $page < $totalPages;
        $hasPrev = $page > 1;

        return response()->json([
            'results' => $words->pluck('word'),
            'totalDocs' => $totalDocs,
            'page' => $page,
            'totalPages' => $totalPages,
            'hasNext' => $hasNext,
            'hasPrev' => $hasPrev,
        ]);
    }
}
