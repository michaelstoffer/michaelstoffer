<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class GalleryRepository
{
    protected string $base;

    public function __construct()
    {
        $this->base = resource_path('content/photography');
    }

    public function all(): array
    {
        return Cache::remember('galleries:all', 3600, function () {
            $items = [];
            foreach (glob($this->base.'/*.md') as $file) {
                $doc = YamlFrontMatter::parseFile($file);
                $m = $doc->matter();
                $items[] = [
                    'slug' => $m['slug'] ?? pathinfo($file, PATHINFO_FILENAME),
                    'title' => $m['title'] ?? '',
                    'description' => $m['description'] ?? '',
                    'cover' => $m['cover'] ?? null,
                    'count' => isset($m['photos']) ? count($m['photos']) : null,
                    'tags' => $m['tags'] ?? [],
                ];
            }
            usort($items, fn ($a, $b) => strcasecmp($a['title'], $b['title']));

            return $items;
        });
    }

    public function find(string $slug): array
    {
        return Cache::remember("gallery:$slug", 3600, function () use ($slug) {
            $file = $this->base."/$slug.md";
            abort_unless(file_exists($file), 404);
            $doc = YamlFrontMatter::parseFile($file);

            return array_merge($doc->matter(), ['body' => $doc->body()]);
        });
    }
}
