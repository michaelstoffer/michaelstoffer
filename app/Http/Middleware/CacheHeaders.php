<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    public function handle(Request $request, Closure $next): Response
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

        $response->headers->set('Vary', 'X-Inertia', false);

        // Stateful, mutating, and Inertia navigation responses may contain session
        // or validation data and must never be stored by a browser or intermediary.
        if (
            (! $request->isMethod('GET'))
            || $request->header('X-Inertia')
            || $request->is('admin', 'admin/*', 'contact')
        ) {
            $response->headers->set('Cache-Control', 'no-store, private');

            return $response;
        }

        // Anonymous HTML may be revalidated, but is not served stale. These routes
        // do not start a Laravel session and contain no user-specific content.
        $response->headers->set('Cache-Control', 'public, max-age=0, must-revalidate');

        return $response;
    }
}
