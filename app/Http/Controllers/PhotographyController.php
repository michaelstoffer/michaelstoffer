<?php

namespace App\Http\Controllers;

use App\Support\GalleryRepository;
use Inertia\Inertia;

class PhotographyController extends Controller
{
    public function index()
    {
        // TODO: replace with Markdown/DB in 7D
        $galleries = app(GalleryRepository::class)->all();

        return Inertia::render('Photography', compact('galleries'));
    }

    public function show(string $slug)
    {
        // Minimal, seed data only. Replace with repository in 7D.
        $map = [
            'portraits' => [
                'title' => 'Portraits',
                'description' => 'Natural light portraits with classic composition.',
                'cover' => '/media/photography/portraits/cover.jpg',
                'photos' => [
                    [
                        'src' => '/media/photography/portraits/p1.jpg',
                        'alt' => 'Soft window light portrait',
                        'w' => 1600, 'h' => 2000, 'orientation' => 'portrait',
                        'tags' => ['people','natural light'], 'camera' => 'Sony A7 IV', 'lens' => '85mm', 'location' => 'Studio', 'date' => '2025-02-12'
                    ],
                    [
                        'src' => '/media/photography/portraits/p2.jpg',
                        'alt' => 'Outdoor portrait with foliage bokeh',
                        'w' => 2000, 'h' => 1600, 'orientation' => 'landscape',
                        'tags' => ['people','outdoor'], 'camera' => 'Sony A7 IV', 'lens' => '55mm', 'location' => 'Park', 'date' => '2025-02-12'
                    ],
                    // ...add more seed images
                ],
            ],
            'brand-sets' => [
                'title' => 'Brand & Product Sets',
                'description' => 'Clean product shots with consistent lighting and color.',
                'cover' => '/media/photography/brand-sets/cover.jpg',
                'photos' => [
                    [
                        'src' => '/media/photography/brand-sets/b1.jpg',
                        'alt' => 'Minimal product on seamless',
                        'w' => 2000, 'h' => 1333, 'orientation' => 'landscape',
                        'tags' => ['product'], 'camera' => 'Sony A7C', 'lens' => '90mm Macro', 'location' => 'Tabletop', 'date' => '2024-11-03'
                    ],
                ],
            ],
            'landscapes' => [
                'title' => 'Landscapes',
                'description' => 'Timeless scenes with strong lines and quiet light.',
                'cover' => '/media/photography/landscapes/cover.jpg',
                'photos' => [
                    [
                        'src' => '/media/photography/landscapes/l1.jpg',
                        'alt' => 'Dunes under golden-hour light',
                        'w' => 2000, 'h' => 1333, 'orientation' => 'landscape',
                        'tags' => ['nature'], 'camera' => 'Sony A7 IV', 'lens' => '24-70mm', 'location' => 'Coast', 'date' => '2023-10-08'
                    ],
                ],
            ],
        ];

        abort_unless(array_key_exists($slug, $map), 404);
        $gallery = app(GalleryRepository::class)->find($slug);
        $related = collect($map)->except($slug)->map(function ($g, $s) {
            return [
                'slug' => $s,
                'title' => $g['title'],
                'cover' => $g['cover'] ?? null,
            ];
        })->values()->all();

        return Inertia::render('Gallery', compact('slug', 'gallery', 'related'));
    }
}
