<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap; use Spatie\Sitemap\Tags\Url;

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
            if (class_exists('App\\Support\\MusicRepository')) {
                foreach (app('App\\Support\\MusicRepository')->all() as $s) {
                    $map->add(Url::create('/music/'.$s['slug']));
                }
            }
            if (class_exists('App\\Support\\GalleryRepository')) {
                foreach (app('App\\Support\\GalleryRepository')->all() as $g) {
                    $map->add(Url::create('/photography/'.$g['slug']));
                }
            }
        } catch (\Throwable $e) { /* keep sitemap resilient */ }

        $map->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated.');
        return self::SUCCESS;
    }
}
