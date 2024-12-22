<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HistoryController extends Controller
{
    // Listar histórico do usuário
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
