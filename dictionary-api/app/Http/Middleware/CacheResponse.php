<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    public function handle($request, Closure $next)
    {
        $key = md5($request->fullUrl());

        if (Cache::has($key)) {
            $response = Cache::get($key);
            $response->headers->set('x-cache', 'HIT');
            return $response;
        }

        $response = $next($request);
        $response->headers->set('x-cache', 'MISS');
        $response->headers->set('x-response-time', microtime(true) - LARAVEL_START);
        
        Cache::put($key, $response, 3600); 

        return $response;
    }
}
