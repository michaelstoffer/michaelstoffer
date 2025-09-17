<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class MusicRepository
{
    protected string $base;
    public function __construct()
    {
        $this->base = resource_path('content/music');
    }

    public function all(): array
    {
        return Cache::remember('music:all', 3600, function () {
            $items = [];
            foreach (glob($this->base.'/*/index.md') as $file) {
                $doc = YamlFrontMatter::parseFile($file);
                $m = $doc->matter();
                $items[] = [
                    'slug' => $m['slug'] ?? basename(dirname($file)),
                    'title' => $m['title'] ?? '',
                    'description' => $m['description'] ?? ($m['summary'] ?? ''),
                    'duration' => $m['duration'] ?? null,
                    'key' => $m['key'] ?? null,
                    'bpm' => $m['bpm'] ?? null,
                    'audioSrc' => $m['audioSrc'] ?? null,
                    'cover' => $m['cover'] ?? null,
                    'socialImage' => $m['socialImage'] ?? null,
                    'videoEmbed' => $m['videoEmbed'] ?? null,
                    'downloads' => $m['downloads'] ?? [],
                    'body' => $doc->body() ?? '',
                    'tags' => $m['tags'] ?? [],
                ];
            }
            // stable sort by title
            usort($items, fn($a,$b) => strcasecmp($a['title'],$b['title']));
            return $items;
        });
    }

    public function find(string $slug): array
    {
        return Cache::remember("music:$slug", 3600, function () use ($slug) {
            $file = $this->base."/$slug/index.md";
            abort_unless(file_exists($file), 404);
            $doc = YamlFrontMatter::parseFile($file);
            return array_merge($doc->matter(), ['body' => $doc->body()]);
        });
    }
}
