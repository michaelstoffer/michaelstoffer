<?php

use App\Support\PostRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

test('latest returns an array of posts', function () {
    $repo = new PostRepository;

    $posts = $repo->latest();

    expect($posts)->toBeArray()->not->toBeEmpty();
});

test('each post has required keys', function () {
    $repo = new PostRepository;
    $post = $repo->latest(1)[0];

    expect($post)->toHaveKeys([
        'slug',
        'title',
        'excerpt',
        'cover',
        'published_at',
        'published_label',
        'modified_at',
        'modified_label',
        'html',
        'tags',
        'featured',
        'status',
    ]);
});

test('latest respects the limit parameter', function () {
    $repo = new PostRepository;

    expect($repo->latest(3))->toHaveCount(3);
    expect($repo->latest(1))->toHaveCount(1);
});

test('posts are sorted by published_at descending', function () {
    $repo = new PostRepository;
    $posts = $repo->latest();

    $dates = array_column($posts, 'published_at');
    $sorted = $dates;
    rsort($sorted);

    expect($dates)->toBe($sorted);
});

test('find returns a single post by slug', function () {
    $repo = new PostRepository;
    $post = $repo->find('10-essential-git-commands-every-developer-should-start-using');

    expect($post['slug'])->toBe('10-essential-git-commands-every-developer-should-start-using');
    expect($post['title'])->not->toBeEmpty();
});

test('find throws 404 for unknown slug', function () {
    $repo = new PostRepository;

    expect(fn () => $repo->find('slug-that-does-not-exist'))
        ->toThrow(HttpException::class);
});

test('publication labels are formatted consistently without browser locale conversion', function () {
    $repo = new PostRepository;
    $post = $repo->find('keeping-controllers-clean-with-laravel-form-requests');

    expect($post['published_at'])->toBe('2026-05-31T08:00:00-04:00')
        ->and($post['published_label'])->toBe('May 31, 2026')
        ->and($post['modified_label'])->toBe('May 31, 2026');
});

test('future and draft posts are hidden unless explicitly requested', function () {
    $repo = new PostRepository(base_path('tests/Fixtures/blog'));

    expect(array_column($repo->latest(), 'slug'))->toBe(['published-post'])
        ->and(array_column($repo->latest(includeUnpublished: true), 'slug'))
        ->toBe(['future-post', 'draft-post', 'published-post'])
        ->and(fn () => $repo->find('future-post'))
        ->toThrow(HttpException::class)
        ->and($repo->find('future-post', includeUnpublished: true)['slug'])->toBe('future-post');
});
