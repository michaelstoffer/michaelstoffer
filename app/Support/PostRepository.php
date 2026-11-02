<?php

// app/Support/PostRepository.php

namespace App\Support;

use Carbon\CarbonImmutable;
use League\CommonMark\CommonMarkConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class PostRepository
{
    use ResolvesWebP;

    protected string $base;

    protected CommonMarkConverter $md;

    public function __construct(?string $base = null)
    {
        $this->base = $base ?? resource_path('content/blog');
        $this->md = new CommonMarkConverter(['html_input' => 'strip', 'allow_unsafe_links' => false]);
    }

    public function latest(int $limit = 100, bool $includeUnpublished = false): array
    {
        $files = array_reverse(glob($this->base.'/*.md'));
        $posts = array_map(fn ($f) => $this->parse($f), $files);

        if (! $includeUnpublished) {
            $posts = array_values(array_filter($posts, fn (array $post): bool => $this->isPublished($post)));
        }

        usort($posts, fn ($a, $b) => strcmp($b['published_at'], $a['published_at']));

        return array_slice($posts, 0, $limit);
    }

    public function find(string $slug, bool $includeUnpublished = false): array
    {
        $file = $this->base."/$slug.md";
        abort_unless(file_exists($file), 404);

        $post = $this->parse($file);
        abort_unless($includeUnpublished || $this->isPublished($post), 404);

        return $post;
    }

    protected function parse(string $file): array
    {
        $doc = YamlFrontMatter::parseFile($file);
        $p = $doc->matter();
        $publishedAt = CarbonImmutable::parse($p['published_at'] ?? now());
        $modifiedAt = isset($p['modified_at']) ? CarbonImmutable::parse($p['modified_at']) : null;

        return [
            'slug' => pathinfo($file, PATHINFO_FILENAME),
            'title' => $p['title'] ?? 'Untitled',
            'excerpt' => $p['excerpt'] ?? null,
            ...$this->resolveCover($p['cover'] ?? null),
            'published_at' => $publishedAt->toIso8601String(),
            'published_label' => $publishedAt->format('F j, Y'),
            'modified_at' => $modifiedAt?->toIso8601String(),
            'modified_label' => $modifiedAt?->format('F j, Y'),
            'html' => $doc->body(),
            'tags' => $p['tags'] ?? [],
            'featured' => (bool) ($p['featured'] ?? false),
            'status' => $p['status'] ?? 'published',
        ];
    }

    protected function isPublished(array $post): bool
    {
        return $post['status'] === 'published'
            && CarbonImmutable::parse($post['published_at'])->lessThanOrEqualTo(now());
    }
}
