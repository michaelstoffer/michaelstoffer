<?php

use App\Support\CaseStudyRepository;

test('responsive cover descriptors use the files actual widths', function () {
    $caseStudy = collect((new CaseStudyRepository)->all())
        ->firstWhere('slug', 'hmy');

    $fullWidth = getimagesize(public_path($caseStudy['cover']))[0];

    expect($caseStudy['coverSrcset'])
        ->toContain('cover-400w.webp 400w')
        ->toContain('cover-800w.webp 800w')
        ->toContain("{$caseStudy['cover']} {$fullWidth}w")
        ->not->toContain('cover.webp 2000w');
});
