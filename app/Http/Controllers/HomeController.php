<?php

namespace App\Http\Controllers;

use App\Support\CaseStudyRepository;
use App\Support\MusicRepository;
use App\Support\PostRepository;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(CaseStudyRepository $cases, PostRepository $posts, MusicRepository $music): Response
    {
        $all = $cases->all();

        // Prefer items explicitly marked as featured; fall back to first 3
        $featured = collect($all)->filter(fn ($cs) => ($cs['featured'] ?? false))->values();
        if ($featured->isEmpty()) {
            $featured = collect($all);
        }

        $caseStudies = $featured->take(3)->map(function ($cs) {
            return [
                'slug' => $cs['slug'],
                'title' => $cs['title'],
                'summary' => $cs['summary'] ?? $cs['result'] ?? '',
                'problem' => $cs['problem'] ?? null,
                'approach' => $cs['approach'] ?? null,
                'result' => $cs['result'] ?? null,
                'tags' => $cs['tags'] ?? [],
                'featured' => $cs['featured'] ?? false,
            ];
        })->all();

        $latestPosts = collect($posts->latest())->take(3);

        $songsAll = $music->all();
        $featured = collect($songsAll)->filter(fn ($s) => ($s['featured'] ?? false))->values();
        if ($featured->isEmpty()) {
            $featured = collect($songsAll);
        }
        $songs = $featured->take(3)->map(fn ($s) => [
            'slug' => $s['slug'], 'title' => $s['title'], 'cover' => $s['cover'] ?? null,
            'duration' => $s['duration'] ?? null, 'audioSrc' => $s['audioSrc'] ?? null,
        ])->all();

        return Inertia::render('Home', [
            'caseStudies' => $caseStudies,
            'songs' => $songs,
            'latestPosts' => $latestPosts,
        ]);
    }
}
