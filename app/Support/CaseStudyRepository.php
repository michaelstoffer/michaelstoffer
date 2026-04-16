<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class CaseStudyRepository
{
    protected string $base;

    public function __construct()
    {
        $this->base = resource_path('content/case-studies');
    }

    public function all(): array
    {
        return Cache::remember('case:all', 3600, function () {
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
                    'cover' => $m['cover'] ?? null,
                    'featured' => $m['featured'] ?? false,
                    'order' => $m['order'] ?? null,
                    'software_order' => $m['software_order'] ?? null,
                ];
            }
            usort($items, function ($a, $b) {
                $aOrder = $a['software_order'] ?? null;
                $bOrder = $b['software_order'] ?? null;

                if ($aOrder !== null && $bOrder !== null) {
                    return $aOrder <=> $bOrder;
                }
                if ($aOrder !== null) return -1;
                if ($bOrder !== null) return 1;

                return strcasecmp($a['title'], $b['title']);
            });

            return $items;
        });
    }

    public function find(string $slug): array
    {
        return Cache::remember("case:$slug", 3600, function () use ($slug) {
            $file = $this->base."/$slug/index.md";
            abort_unless(is_file($file), 404);
            $doc = YamlFrontMatter::parseFile($file);

            return array_merge($doc->matter(), ['body' => (string) $doc->body()]);
        });
    }
}
