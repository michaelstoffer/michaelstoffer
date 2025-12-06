<?php

namespace App\Http\Controllers;

use App\Support\MusicRepository;
use Inertia\Inertia;
use Inertia\Response;

class MusicController extends Controller
{
    public function index(): Response
    {
        $songs = app(MusicRepository::class)->all();

        return Inertia::render('Music', [
            ...compact('songs'),
            'amr' => '/media/music/apple_music_replay_25.mp4'
        ]);
    }

    public function show(string $slug): Response
    {
        $library = collect(app(MusicRepository::class)->all())->mapWithKeys(function ($item) {
            return [$item['slug'] => $item];
        });

        abort_unless($library->has($slug), 404);

        return Inertia::render('Song', [
            'slug' => $slug,
            'song' => $library->get($slug),
            'related' => $library->except($slug)->take(2)->values()->all(),
        ]);
    }
}
