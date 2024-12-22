<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Fullstack Challenge 🏅 - Dictionary']);
    }
}
