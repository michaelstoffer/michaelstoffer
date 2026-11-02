<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class MusicRepository
{
    use ResolvesWebP;

    protected string $base;

    protected SafeMarkdownRenderer $renderer;

    public function __construct(?string $base = null, ?SafeMarkdownRenderer $renderer = null)
    {
        $this->base = $base ?? resource_path('content/music');
        $this->renderer = $renderer ?? app(SafeMarkdownRenderer::class);
    }

    public function all(): array
    {
        return Cache::remember('music:all:v2', 3600, function () {
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
                    ...$this->resolveCover($m['cover'] ?? null),
                    'socialImage' => $m['socialImage'] ?? null,
                    'videoEmbed' => $m['videoEmbed'] ?? null,
                    'downloads' => $m['downloads'] ?? [],
                    'tags' => $m['tags'] ?? [],
                    'featured' => (bool) ($m['featured'] ?? false),
                    'order' => $m['order'] ?? null,
                ];
            }
            // stable sort by title
            usort($items, fn ($a, $b) => strcasecmp($a['title'], $b['title']));

            return $items;
        });
    }

    public function find(string $slug): array
    {
        return Cache::remember("music:$slug:v2", 3600, function () use ($slug) {
            $file = $this->base."/$slug/index.md";
            abort_unless(file_exists($file), 404);
            $doc = YamlFrontMatter::parseFile($file);

            $matter = $doc->matter();

            return [
                ...$matter,
                'slug' => $matter['slug'] ?? $slug,
                ...$this->resolveCover($matter['cover'] ?? null),
                'html' => $this->renderer->render($doc->body()),
            ];
        });
    }
}
