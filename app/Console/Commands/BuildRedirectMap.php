<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\PostRepository;

class BuildRedirectMap extends Command
{
    protected $signature = 'seo:build-redirects
        {--gsc=storage/app/seo/gsc_404.csv}
        {--log=storage/app/seo/access.log}
        {--legacy=storage/app/seo/legacy-post-slugs.txt}';

    protected $description = 'Build redirect map from GSC 404 CSV + access logs';

    public function handle(): int
    {
        $this->info('Loading inputs...');
        $rows = $this->loadGsc($this->option('gsc'));
        $hits = $this->aggregateLog($this->option('log'));
        $legacy = $this->loadLegacy($this->option('legacy'));

        // Known post slugs from repository (swap for DB/Markdown source)
        $posts = method_exists(app(), 'make') && class_exists(PostRepository::class)
            ? array_map(fn($p) => $p['slug'], app(PostRepository::class)->latest(500))
            : [];

        $map = [];
        foreach ($rows as $path) {
            $norm = $this->normalize($path);
            if ($norm === '/' || $norm === '') continue;

            // pattern rules
            [$dest, $status, $confidence, $note] = $this->patternRedirect($norm);

            // slug rules
            if (!$dest && substr_count($norm, '/') === 1) {
                $slug = ltrim($norm, '/');
                if (in_array($slug, $posts, true)) {
                    $dest = "/blog/$slug"; $status = 301; $confidence = 1.00; $note = 'exact post slug';
                } elseif (in_array($slug, $legacy, true)) {
                    $dest = "/blog/$slug"; $status = 301; $confidence = 0.95; $note = 'legacy list';
                } elseif ($posts) {
                    [$best, $score] = $this->bestMatch($slug, $posts);
                    if ($score >= 0.88) { $dest = "/blog/$best"; $status = 301; $confidence = round($score, 2); $note = 'fuzzy'; }
                }
            }

            // asset rules
            if (!$dest && preg_match('~^/(wp-content|uploads|content)/~', $norm)) {
                $dest = '';
                $status = 410; $confidence = 0.9; $note = 'legacy asset (gone)';
            }

            // default: leave unmapped for manual review
            $map[$norm] = [
                'source' => $norm,
                'dest' => $dest,
                'status' => $status ?: '',
                'confidence' => $confidence ?: '',
                'hits' => $hits[$norm] ?? 0,
                'note' => $note ?: '',
            ];
        }

        // Sort by hits desc then source asc
        uasort($map, function($a,$b){ return [$b['hits'],$a['source']] <=> [$a['hits'],$b['source']]; });

        $this->writeCsv($map, storage_path('app/seo/redirect_map.csv'));
        $this->writeNginx($map, storage_path('app/seo/nginx_map.conf'));
        $this->writeHtaccess($map, storage_path('app/seo/.htaccess_snippets.conf'));

        $this->info('Redirect map built.');
        return self::SUCCESS;
    }

    protected function loadGsc(string $file): array
    {
        if (!is_file($file)) return [];
        $rows = array_map('str_getcsv', file($file));
        $paths = [];
        foreach ($rows as $r) {
            $url = $r[0] ?? '';
            if (!preg_match('~https?://[^/]+(?P<path>/[^?\s#]*)~i', $url, $m)) continue;
            $paths[] = $m['path'];
        }
        return array_values(array_unique($paths));
    }

    protected function aggregateLog(string $file): array
    {
        if (!is_file($file)) return [];
        $hits = [];
        $fh = fopen($file, 'r');
        while (($line = fgets($fh)) !== false) {
            if (!preg_match('~"[A-Z]+\s+([^\s\"]+)\s+HTTP~', $line, $m)) continue;
            $path = parse_url($m[1], PHP_URL_PATH) ?: '/';
            $path = $this->normalize($path);
            $hits[$path] = ($hits[$path] ?? 0) + 1;
        }
        fclose($fh);
        return $hits;
    }

    protected function loadLegacy(string $file): array
    {
        if (!is_file($file)) return [];
        return array_values(array_filter(array_map('trim', file($file))));
    }

    protected function normalize(string $path): string
    {
        $p = strtolower(parse_url($path, PHP_URL_PATH) ?: '/');
        if ($p !== '/' && str_ends_with($p, '/')) $p = rtrim($p, '/');
        return $p;
    }

    protected function patternRedirect(string $p): array
    {
        // dest, status, confidence, note
        if (preg_match('~^/(category|tag|author)/~', $p)) return ['/blog', 301, 1.0, 'wp archive'];
        if (preg_match('~^/blog/page/\d+$~', $p)) return ['/blog', 301, 1.0, 'blog pagination'];
        if (preg_match('~^/(feed|rss|atom)$~', $p) || preg_match('~^/blog/(feed|rss|atom)$~', $p)) return ['/rss.xml', 301, 1.0, 'feed'];
        if (preg_match('~^/(services|service|what-i-do|portfolio|work|projects)$~', $p)) return ['/software', 301, 1.0, 'section rename'];
        if (preg_match('~^/(contact-us|contact-me)$~', $p)) return ['/contact', 301, 1.0, 'section rename'];
        if ($p === '/about-me') return ['/about', 301, 1.0, 'section rename'];
        if (preg_match('~^/(wp-admin|wp-login\.php|wp-json|xmlrpc\.php)(/|$)~', $p)) return ['', 410, 1.0, 'wp endpoint'];
        return ['', 0, 0.0, ''];
    }

    protected function jwSim(string $a, string $b): float
    {
        // Simple Jaro-Winkler approx
        similar_text($a, $b, $pct); return $pct / 100.0;
    }

    protected function bestMatch(string $slug, array $pool): array
    {
        $best = ''; $bestScore = 0.0;
        foreach ($pool as $cand) {
            $s = $this->jwSim($slug, $cand);
            if ($s > $bestScore) { $bestScore = $s; $best = $cand; }
        }
        return [$best, $bestScore];
    }

    protected function writeCsv(array $map, string $out): void
    {
        if (! is_dir(dirname($out))) { mkdir(dirname($out), 0755, true); }
        $fh = fopen($out, 'w');
        fputcsv($fh, ['source_path','destination','status','confidence','hits','note']);
        foreach ($map as $row) {
            fputcsv($fh, [$row['source'], $row['dest'], $row['status'], $row['confidence'], $row['hits'], $row['note']]);
        }
        fclose($fh);
    }

    protected function writeNginx(array $map, string $out): void
    {
        $lines = ["# Generated by seo:build-redirects", "map \$request_uri \$redirect_target {", "    default \"\";" ];
        foreach ($map as $r) {
            if ($r['dest'] && (int)$r['status'] === 301) {
                $from = addcslashes($r['source'], ' "');
                $to = addcslashes($r['dest'], ' "');
                $lines[] = "    $from \"$to\";";
            }
        }
        $lines[] = "}";
        $lines[] = "# usage in server {}: if (\$redirect_target != \"\") { return 301 \$redirect_target; }";
        if (! is_dir(dirname($out))) { mkdir(dirname($out), 0755, true); }
        file_put_contents($out, implode("\n", $lines));
    }

    protected function writeHtaccess(array $map, string $out): void
    {
        $lines = ["# Generated 301 rules (add after canonical rules)"];
        foreach ($map as $r) {
            if ($r['dest'] && (int)$r['status'] === 301) {
                $from = ltrim($r['source'], '/');
                $to = $r['dest'];
                $lines[] = "Redirect 301 /$from $to";
            }
        }
        $lines[] = "# 410 rules";
        foreach ($map as $r) {
            if ((int)$r['status'] === 410) {
                $from = ltrim($r['source'], '/');
                $lines[] = "Redirect gone /$from";
            }
        }
        if (! is_dir(dirname($out))) { mkdir(dirname($out), 0755, true); }
        file_put_contents($out, implode("\n", $lines));
    }
}
