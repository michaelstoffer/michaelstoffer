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
        $casesAll = $cases->all();
        $featuredCases = collect($casesAll)->filter(fn ($cs) => ($cs['featured'] ?? false))->values();
        if ($featuredCases->isEmpty()) {
            $featuredCases = collect($casesAll);
        }
        $caseStudies = $featuredCases->take(3)->map(fn ($cs) => [
            'slug' => $cs['slug'],
            'title' => $cs['title'],
            'summary' => $cs['summary'] ?? $cs['result'] ?? '',
            'problem' => $cs['problem'] ?? null,
            'approach' => $cs['approach'] ?? null,
            'result' => $cs['result'] ?? null,
            'tags' => $cs['tags'] ?? [],
        ])->all();

        $latestPosts = collect($posts->latest(3));

        $songsAll = $music->all();
        $featuredSongs = collect($songsAll)->filter(fn ($s) => ($s['featured'] ?? false))->values();
        if ($featuredSongs->isEmpty()) {
            $featuredSongs = collect($songsAll);
        }
        $songs = $featuredSongs->take(3)->map(fn ($s) => [
            'slug' => $s['slug'],
            'title' => $s['title'],
            'summary' => $s['summary'] ?? $s['description'] ?? '',
            'cover' => $s['cover'] ?? null,
            'duration' => $s['duration'] ?? null,
            'audioSrc' => $s['audioSrc'] ?? null,
        ])->all();

        return Inertia::render('Home', [
            'caseStudies' => $caseStudies,
            'songs' => $songs,
            'latestPosts' => $latestPosts,
            'amr' => '/media/music/apple_music_replay_25.mp4'
        ]);
    }
}
