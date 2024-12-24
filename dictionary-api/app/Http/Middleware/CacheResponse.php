<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    public function handle($request, Closure $next)
    {
        $nonCacheableRoutes = [
            'user/me/favorites',
            'user/me/history',
        ];

        // Utilizando fullUrl() para garantir que a URL completa seja verificada, incluindo parâmetros de query
        $fullUrl = $request->fullUrl();
        
        foreach ($nonCacheableRoutes as $route) {
            if (strpos($fullUrl, $route) !== false) {
                return $next($request);
            }
        }

        // Gerenciamento de cache para outras rotas
        $key = md5($fullUrl);

        if (Cache::has($key)) {
            $cachedContent = Cache::get($key);
            $response = new Response($cachedContent);
            $response->headers->set('x-cache', 'HIT');
            return $response;
        }

        $response = $next($request);

        Cache::put($key, $response->getContent(), 3600);

        $response->headers->set('x-cache', 'MISS');
        $response->headers->set('x-response-time', microtime(true) - LARAVEL_START);

        return $response;
    }
}
