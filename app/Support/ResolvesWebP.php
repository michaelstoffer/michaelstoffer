<?php

namespace App\Support;

trait ResolvesWebP
{
    /**
     * Swap a cover path to its WebP equivalent if one exists on disk,
     * and return a srcset string for the responsive variants generated at build time.
     *
     * Returns an array: ['cover' => string|null, 'coverSrcset' => string|null]
     */
    protected function resolveCover(?string $path): array
    {
        if (! $path) {
            return ['cover' => null, 'coverSrcset' => null];
        }

        $info = pathinfo($path);
        $webpPath = $info['dirname'].'/'.$info['filename'].'.webp';
        $webpDisk = public_path($webpPath);

        // Prefer WebP if it exists
        $cover = file_exists($webpDisk) ? $webpPath : $path;

        // Build srcset from pre-generated responsive variants.
        $base = $info['dirname'].'/'.$info['filename'];
        $variants = [400, 800, 1200];
        $parts = [];
        $largestVariant = 0;

        foreach ($variants as $w) {
            $variantDisk = public_path("{$base}-{$w}w.webp");

            if (file_exists($variantDisk)) {
                $parts[] = "{$base}-{$w}w.webp {$w}w";
                $largestVariant = $w;
            }
        }

        if ($parts) {
            $dimensions = @getimagesize(public_path($cover));
            $fullWidth = $dimensions[0] ?? null;

            // Never advertise an invented width: it can make the browser select
            // a much larger source than the rendered slot actually needs.
            if ($fullWidth && $fullWidth > $largestVariant) {
                $parts[] = "{$cover} {$fullWidth}w";
            }
        }

        return ['cover' => $cover, 'coverSrcset' => $parts ? implode(', ', $parts) : null];
    }
}
