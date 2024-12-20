<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HistoryController extends Controller
{
    // Adicionar palavra ao histórico
    public function addHistory($word)
    {
        $user = Auth::user();

        History::create([
            'user_id' => $user->_id,
            'word' => $word,
            'viewed_at' => now(),
        ]);

        return response()->json(['message' => 'Palavra adicionada ao histórico'], 201);
    }

    // Listar histórico do usuário
    public function getHistory()
    {
        $user = Auth::user();

        dd($user->favorites());

        $history = $user->history()->orderBy('viewed_at', 'desc')->get();

        return response()->json(['results' => $history], 200);
    }
}
