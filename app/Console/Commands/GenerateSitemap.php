<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap; use Spatie\Sitemap\Tags\Url;
use App\Support\PostRepository;
use App\Support\GalleryRepository;
use App\Support\MusicRepository;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:sitemap';
    protected $description = 'Generate sitemap.xml';

    public function handle(): int
    {
        $site = config('app.url');
        $map = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/about'))
            ->add(Url::create('/software'))
            ->add(Url::create('/music'))
            ->add(Url::create('/photography'))
            ->add(Url::create('/blog'));

        // Optional dynamic sections (wire when repos exist)
        try {
            if (class_exists(PostRepository::class)) {
                $posts = app(PostRepository::class)->latest(5000);
                foreach ($posts as $p) {
                    $map->add(Url::create('/blog/'.$p['slug']));
                }
            }
            if (class_exists(MusicRepository::class)) {
                //dd('music');
                $songs = app(MusicRepository::class)->all();
                foreach ($songs as $s) {
                    $map->add(Url::create('/music/'.$s['slug']));
                }
            }
            if (class_exists(GalleryRepository::class)) {
                foreach (app(GalleryRepository::class)->all() as $g) {
                    $map->add(Url::create('/photography/'.$g['slug']));
                }
            }
        } catch (\Throwable $e) { /* keep sitemap resilient */ }

        $map->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated.');
        return self::SUCCESS;
    }
}
