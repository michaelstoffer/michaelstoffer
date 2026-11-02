<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Throwable;

class ValidateBlogContent extends Command
{
    protected $signature = 'blog:validate';

    protected $description = 'Validate blog front matter, dates, statuses, and referenced cover images';

    public function handle(): int
    {
        $files = glob(resource_path('content/blog/*.md')) ?: [];
        $errors = [];
        $warnings = [];
        $titles = [];

        foreach ($files as $file) {
            $name = basename($file);

            try {
                $document = YamlFrontMatter::parseFile($file);
                $matter = $document->matter();
            } catch (Throwable $exception) {
                $errors[] = "$name: invalid front matter ({$exception->getMessage()})";

                continue;
            }

            foreach (['title', 'excerpt', 'published_at', 'tags'] as $required) {
                if (! isset($matter[$required]) || $matter[$required] === '' || $matter[$required] === []) {
                    $errors[] = "$name: missing required '$required' front-matter field";
                }
            }

            if (isset($matter['published_at'])) {
                try {
                    CarbonImmutable::parse($matter['published_at']);
                } catch (Throwable) {
                    $errors[] = "$name: published_at is not a valid date and time";
                }
            }

            if (isset($matter['modified_at'])) {
                try {
                    CarbonImmutable::parse($matter['modified_at']);
                } catch (Throwable) {
                    $errors[] = "$name: modified_at is not a valid date and time";
                }
            }

            $status = $matter['status'] ?? 'published';
            if (! in_array($status, ['draft', 'published'], true)) {
                $errors[] = "$name: status must be 'draft' or 'published'";
            }

            if (isset($matter['tags']) && ! is_array($matter['tags'])) {
                $errors[] = "$name: tags must be an array";
            }

            if (! empty($matter['cover'])) {
                $cover = public_path(ltrim($matter['cover'], '/'));
                if (! is_file($cover)) {
                    $errors[] = "$name: cover image does not exist at {$matter['cover']}";
                }
            }

            if (trim($document->body()) === '') {
                $errors[] = "$name: post body is empty";
            }

            if (! empty($matter['title'])) {
                $normalizedTitle = mb_strtolower(trim($matter['title']));
                if (isset($titles[$normalizedTitle])) {
                    $warnings[] = "$name: title duplicates {$titles[$normalizedTitle]}";
                } else {
                    $titles[$normalizedTitle] = $name;
                }
            }
        }

        foreach ($warnings as $warning) {
            $this->warn($warning);
        }

        foreach ($errors as $error) {
            $this->error($error);
        }

        if ($errors !== []) {
            $this->newLine();
            $this->error(count($errors).' blog validation error(s) found.');

            return self::FAILURE;
        }

        $this->info(count($files).' blog posts validated successfully.');

        return self::SUCCESS;
    }
}
