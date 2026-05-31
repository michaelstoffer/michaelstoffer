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
        if (!$path) {
            return ['cover' => null, 'coverSrcset' => null];
        }

        $info = pathinfo($path);
        $webpPath = $info['dirname'] . '/' . $info['filename'] . '.webp';
        $webpDisk = public_path($webpPath);

        // Prefer WebP if it exists
        $cover = file_exists($webpDisk) ? $webpPath : $path;

        // Build srcset from pre-generated responsive variants
        $srcset = null;
        $base = $info['dirname'] . '/' . $info['filename'];
        $variants = [400, 800, 1200];
        $parts = [];
        foreach ($variants as $w) {
            $variantDisk = public_path("{$base}-{$w}w.webp");
            if (file_exists($variantDisk)) {
                $parts[] = "{$base}-{$w}w.webp {$w}w";
            }
        }
        if ($parts) {
            // Include the full-size WebP as the largest descriptor
            $parts[] = "{$cover} 2000w";
            $srcset = implode(', ', $parts);
        }

        return ['cover' => $cover, 'coverSrcset' => $srcset];
    }
}
