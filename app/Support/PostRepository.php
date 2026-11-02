<?php

// app/Support/PostRepository.php

namespace App\Support;

use Carbon\CarbonImmutable;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class PostRepository
{
    use ResolvesWebP;

    protected string $base;

    protected SafeMarkdownRenderer $renderer;

    public function __construct(?string $base = null, ?SafeMarkdownRenderer $renderer = null)
    {
        $this->base = $base ?? resource_path('content/blog');
        $this->renderer = $renderer ?? app(SafeMarkdownRenderer::class);
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

        $post = $this->parse($file, includeHtml: true);
        abort_unless($includeUnpublished || $this->isPublished($post), 404);

        return $post;
    }

    protected function parse(string $file, bool $includeHtml = false): array
    {
        $doc = YamlFrontMatter::parseFile($file);
        $p = $doc->matter();
        $publishedAt = CarbonImmutable::parse($p['published_at'] ?? now());
        $modifiedAt = isset($p['modified_at']) ? CarbonImmutable::parse($p['modified_at']) : null;

        $post = [
            'slug' => pathinfo($file, PATHINFO_FILENAME),
            'title' => $p['title'] ?? 'Untitled',
            'excerpt' => $p['excerpt'] ?? null,
            ...$this->resolveCover($p['cover'] ?? null),
            'published_at' => $publishedAt->toIso8601String(),
            'published_label' => $publishedAt->format('F j, Y'),
            'modified_at' => $modifiedAt?->toIso8601String(),
            'modified_label' => $modifiedAt?->format('F j, Y'),
            'tags' => $p['tags'] ?? [],
            'featured' => (bool) ($p['featured'] ?? false),
            'status' => $p['status'] ?? 'published',
        ];

        if ($includeHtml) {
            $post['html'] = $this->renderer->render($doc->body());
        }

        return $post;
    }

    protected function isPublished(array $post): bool
    {
        return $post['status'] === 'published'
            && CarbonImmutable::parse($post['published_at'])->lessThanOrEqualTo(now());
    }
}
