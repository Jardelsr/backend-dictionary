<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WordController extends Controller
{
    // Adicionar palavra como favorita
    public function addFavorite(Request $request, $word)
    {
        $user = Auth::user();

        $favorite = Word::create([
            'user_id' => $user->_id,
            'word' => $word,
            'added_at' => now(),
        ]);

        return response()->json(['message' => 'Palavra adicionada aos favoritos'], 201);
    }

    // Remover palavra dos favoritos
    public function removeFavorite($word)
    {
        $user = Auth::user();

        dd($user->favorites());

        //$user->favorites()->where('word', $word)->delete();

        // usar esse caso de errado
        //Word::where('user_id', $user->id)->where('word', $word)->delete();

        return response()->json(['message' => 'Palavra removida dos favoritos'], 200);
    }

    // Listar palavras do dicionário (proxy para a API externa)
    public function getDictionaryEntries(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);
        
        $apiUrl = "https://api.dictionaryapi.dev/api/v2/entries/en/$search";

        $response = @file_get_contents($apiUrl);
        if ($response === FALSE) {
            return response()->json(['message' => 'Erro ao buscar a palavra na API'], 400);
        }

        return response()->json(json_decode($response), 200);
    }
}
