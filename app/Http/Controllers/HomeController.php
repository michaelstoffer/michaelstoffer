<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function __invoke()
    {
        // TODO: Replace with real source (DB or Markdown). Keep brief excerpts.
        $latestPosts = [
            // ['title' => 'Why I prefer Inertia for small sites', 'slug' => 'inertia-small-sites', 'date' => '2025-08-22', 'excerpt' => 'Notes on DX and simplicity...'],
        ];

        return Inertia::render('Home', compact('latestPosts'));
    }
}
