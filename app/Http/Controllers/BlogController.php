<?php

namespace App\Http\Controllers;

use App\Support\PostRepository;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        // TODO: Replace with DB/Markdown repository once ready
        $posts = app(PostRepository::class)->latest();

        return Inertia::render('Blog', compact('posts'));
    }

    public function show(string $slug)
    {
        // TODO: Replace with DB or Markdown repository
        $post = app(PostRepository::class)->find($slug);

        return Inertia::render('Post', compact('post'));
    }
}
