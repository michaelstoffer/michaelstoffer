<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class CaseStudyRepository
{
    use ResolvesWebP;

    protected string $base;

    protected SafeMarkdownRenderer $renderer;

    public function __construct(?string $base = null, ?SafeMarkdownRenderer $renderer = null)
    {
        $this->base = $base ?? resource_path('content/case-studies');
        $this->renderer = $renderer ?? app(SafeMarkdownRenderer::class);
    }

    public function all(): array
    {
        return Cache::remember('case:all:v3', 3600, function () {
            $items = [];
            foreach (glob($this->base.'/*/index.md') as $file) {
                $doc = YamlFrontMatter::parseFile($file);
                $m = $doc->matter();
                $slug = $m['slug'] ?? basename(dirname($file));
                $items[] = [
                    'slug' => $slug,
                    'title' => $m['title'] ?? $slug,
                    'summary' => $m['summary'] ?? ($m['result'] ?? ''),
                    'problem' => $m['problem'] ?? null,
                    'approach' => $m['approach'] ?? null,
                    'result' => $m['result'] ?? null,
                    'tags' => $m['tags'] ?? [],
                    ...$this->resolveCover($m['cover'] ?? null),
                    'featured' => $m['featured'] ?? false,
                    'order' => $m['order'] ?? null,
                    'software_order' => $m['software_order'] ?? null,
                    'published_at' => $m['published_at'] ?? null,
                ];
            }
            usort($items, function ($a, $b) {
                $aPublished = strtotime($a['published_at'] ?? '') ?: 0;
                $bPublished = strtotime($b['published_at'] ?? '') ?: 0;

                if ($aPublished !== $bPublished) {
                    return $bPublished <=> $aPublished;
                }

                $aOrder = $a['software_order'] ?? null;
                $bOrder = $b['software_order'] ?? null;

                if ($aOrder !== null && $bOrder !== null) {
                    return $aOrder <=> $bOrder;
                }
                if ($aOrder !== null) {
                    return -1;
                }
                if ($bOrder !== null) {
                    return 1;
                }

                return strcasecmp($a['title'], $b['title']);
            });

            return $items;
        });
    }

    public function find(string $slug): array
    {
        return Cache::remember("case:$slug:v2", 3600, function () use ($slug) {
            $file = $this->base."/$slug/index.md";
            abort_unless(is_file($file), 404);
            $doc = YamlFrontMatter::parseFile($file);

            return array_merge($doc->matter(), [
                'html' => $this->renderer->render($doc->body()),
            ]);
        });
    }
}
