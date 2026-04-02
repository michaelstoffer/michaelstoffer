<?php

use App\Support\CaseStudyRepository;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::forget('case:all');
    Cache::forget('case:hubspot-onboarding-sync');
});

test('all returns an array of case studies', function () {
    $repo = new CaseStudyRepository;

    expect($repo->all())->toBeArray()->not->toBeEmpty();
});

test('each case study has required keys', function () {
    $repo = new CaseStudyRepository;
    $case = $repo->all()[0];

    expect($case)->toHaveKeys(['slug', 'title', 'summary', 'tags', 'cover', 'featured']);
});

test('case studies are sorted alphabetically by title', function () {
    $repo = new CaseStudyRepository;
    $titles = array_column($repo->all(), 'title');
    $sorted = $titles;
    usort($sorted, 'strcasecmp');

    expect($titles)->toBe($sorted);
});

test('find returns a case study by slug', function () {
    $repo = new CaseStudyRepository;
    $case = $repo->find('hubspot-onboarding-sync');

    expect($case['title'])->toBe('HubSpot Onboarding Sync');
});

test('find includes body content', function () {
    $repo = new CaseStudyRepository;
    $case = $repo->find('hubspot-onboarding-sync');

    expect($case)->toHaveKey('body');
});

test('find caches the result', function () {
    $repo = new CaseStudyRepository;
    $repo->find('hubspot-onboarding-sync');

    expect(Cache::has('case:hubspot-onboarding-sync'))->toBeTrue();
});

test('find throws 404 for unknown slug', function () {
    $repo = new CaseStudyRepository;

    expect(fn () => $repo->find('no-such-case-study'))
        ->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});
