<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    public function handle($request, Closure $next)
    {
        $key = md5($request->fullUrl());

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
