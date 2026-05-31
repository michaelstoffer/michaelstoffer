<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $path = $request->path();

        // Vite-fingerprinted assets: content-hash in filename → immutable for 1 year
        if (str_starts_with($path, 'assets/') || str_starts_with($path, 'build/assets/')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            return $response;
        }

        // Static media: images, audio, fonts → 30 days
        if (str_starts_with($path, 'media/') || str_starts_with($path, 'fonts/')) {
            $response->headers->set('Cache-Control', 'public, max-age=2592000, stale-while-revalidate=86400');
            return $response;
        }

        // Favicon, manifest, robots, etc.
        if (preg_match('/\.(ico|webmanifest|txt|xml)$/', $path)) {
            $response->headers->set('Cache-Control', 'public, max-age=86400');
            return $response;
        }

        // HTML / Inertia responses: always fresh
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $response;
    }
}
