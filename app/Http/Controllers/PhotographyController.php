<?php

namespace App\Http\Controllers;

use App\Support\GalleryRepository;
use Inertia\Inertia;

class PhotographyController extends Controller
{
    public function index()
    {
        $galleries = app(GalleryRepository::class)->all();

        return Inertia::render('Photography', compact('galleries'));
    }

    public function show(string $slug)
    {
        $library = collect(app(GalleryRepository::class)->all())->mapWithKeys(function ($item) {
            return [$item['slug'] => $item];
        });

        abort_unless($library->has($slug), 404);

        return Inertia::render('Gallery', [
            'slug' => $slug,
            'gallery' => app(GalleryRepository::class)->find($slug),
            'related' => $library->except($slug)->take(3)->values()->all(),
        ]);
    }
}
