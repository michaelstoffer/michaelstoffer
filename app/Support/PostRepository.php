<?php

// app/Support/PostRepository.php

namespace App\Support;

use League\CommonMark\CommonMarkConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class PostRepository
{
    protected string $base;

    protected CommonMarkConverter $md;

    public function __construct()
    {
        $this->base = resource_path('content/blog');
        $this->md = new CommonMarkConverter(['html_input' => 'strip', 'allow_unsafe_links' => false]);
    }

    public function latest(int $limit = 100): array
    {
        $files = array_reverse(glob($this->base.'/*.md'));
        $posts = array_map(fn ($f) => $this->parse($f), $files);
        usort($posts, fn ($a, $b) => strcmp($b['published_at'], $a['published_at']));

        return array_slice($posts, 0, $limit);
    }

    public function find(string $slug): array
    {
        $file = $this->base."/$slug.md";
        abort_unless(file_exists($file), 404);

        return $this->parse($file);
    }

    protected function parse(string $file): array
    {
        $doc = YamlFrontMatter::parseFile($file);
        $p = $doc->matter();

        return [
            'slug' => pathinfo($file, PATHINFO_FILENAME),
            'title' => $p['title'] ?? 'Untitled',
            'excerpt' => $p['excerpt'] ?? null,
            'cover' => $p['cover'] ?? null,
            'published_at' => $p['published_at'] ?? now()->toDateString(),
            'modified_at' => $p['modified_at'] ?? null,
            'html' => $doc->body(),
            'tags' => $p['tags'] ?? [],
            'featured' => (bool) ($p['featured'] ?? false),
        ];
    }
}
