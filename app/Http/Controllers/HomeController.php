<?php

namespace App\Http\Controllers;

use App\Support\PostRepository;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $latestPosts = collect(app(PostRepository::class)->latest())->take(3);

        return Inertia::render('Home', compact('latestPosts'));
    }
}
