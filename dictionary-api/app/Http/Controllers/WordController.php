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
    // Adicionar palavra como favorita
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

    // Remover palavra dos favoritos
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
            // Capturar exceções e retornar erro genérico
            return response()->json([
                'message' => 'Um erro ocorrou ao tentar remover a palavra dos favoritos.'
            ], 500);
        }
    }

    // Retorna os detalhes da palavra
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
     * Retorna a lista de palavras do dicionário com paginação e suporte a busca.
     */
    public function getDictionaryEntries(Request $request)
    {
        // Parâmetros de entrada
        $search = $request->query('search', '');
        $limit = (int) $request->query('limit', 10); // Limite padrão de 10 palavras por página
        $page = (int) $request->query('page', 1); // Página padrão 1

        // Query base
        $query = Word::query();

        // Filtro de busca
        if (!empty($search)) {
            $query->where('word', 'like', "{$search}%");
        }

        // Paginação
        $totalDocs = $query->count();
        $words = $query->orderBy('word', 'asc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        // Cálculo de páginas
        $totalPages = (int) ceil($totalDocs / $limit);
        $hasNext = $page < $totalPages;
        $hasPrev = $page > 1;

        // Resposta no formato solicitado
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
