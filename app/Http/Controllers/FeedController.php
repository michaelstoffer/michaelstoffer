<?php

namespace App\Http\Controllers;

class FeedController extends Controller
{
    public function rss()
    {
        // Swap this repository for your real source (DB or Markdown)
        $posts = app(\App\Support\PostRepository::class)->latest(20);
        $xml = view('feed.rss', ['posts' => $posts])->render();

        return response($xml, 200)->header('Content-Type', 'application/rss+xml')->setSharedMaxAge(3600);
    }
}
