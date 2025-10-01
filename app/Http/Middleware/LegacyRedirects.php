<?php

namespace App\Http\Middleware;

use App\Support\PostRepository;
use Closure;
use Illuminate\Http\Request;

class LegacyRedirects
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($response->getStatusCode() !== 404) {
            return $response;
        }

        $path = trim($request->path(), '/');
        if ($path && ! str_contains($path, '/')) {
            // single segment like /old-post-slug
            $repo = app(PostRepository::class);
            try {
                $post = $repo->find($path);

                return redirect()->to('/blog/'.$post['slug'], 301);
            } catch (\Throwable $e) { /* not a known post */
            }
        }

        return $response; // fall through to 404
    }
}
