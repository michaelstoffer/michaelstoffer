<?php

use App\Support\CaseStudyRepository;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

test('case studies honor software order before alphabetizing the remainder', function () {
    $repo = new CaseStudyRepository;
    $items = $repo->all();
    $ordered = array_values(array_filter($items, fn (array $item) => $item['software_order'] !== null));
    $unordered = array_values(array_filter($items, fn (array $item) => $item['software_order'] === null));

    expect(array_column($ordered, 'software_order'))->toBe([1, 2, 3]);

    $titles = array_column($unordered, 'title');
    $alphabetized = $titles;
    usort($alphabetized, 'strcasecmp');

    expect($titles)->toBe($alphabetized);
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
        ->toThrow(HttpException::class);
});
