<?php

use App\Support\GalleryRepository;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::forget('galleries:all');
    Cache::forget('gallery:animals');
});

test('all returns an array of galleries', function () {
    $repo = new GalleryRepository;

    expect($repo->all())->toBeArray()->not->toBeEmpty();
});

test('each gallery has required keys', function () {
    $repo = new GalleryRepository;
    $gallery = $repo->all()[0];

    expect($gallery)->toHaveKeys(['slug', 'title', 'description', 'cover', 'tags']);
});

test('galleries are sorted alphabetically by title', function () {
    $repo = new GalleryRepository;
    $titles = array_column($repo->all(), 'title');
    $sorted = $titles;
    usort($sorted, 'strcasecmp');

    expect($titles)->toBe($sorted);
});

test('find returns a gallery by slug', function () {
    $repo = new GalleryRepository;
    $gallery = $repo->find('animals');

    expect($gallery['slug'])->toBe('animals');
    expect($gallery['title'])->not->toBeEmpty();
});

test('find includes body content', function () {
    $repo = new GalleryRepository;
    $gallery = $repo->find('animals');

    expect($gallery)->toHaveKey('body');
});

test('find caches the result', function () {
    $repo = new GalleryRepository;
    $repo->find('animals');

    expect(Cache::has('gallery:animals'))->toBeTrue();
});

test('find throws 404 for unknown slug', function () {
    $repo = new GalleryRepository;

    expect(fn () => $repo->find('no-such-gallery'))
        ->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});
