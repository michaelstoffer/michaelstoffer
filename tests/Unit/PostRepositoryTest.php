<?php

use App\Support\PostRepository;

test('latest returns an array of posts', function () {
    $repo = new PostRepository;

    $posts = $repo->latest();

    expect($posts)->toBeArray()->not->toBeEmpty();
});

test('each post has required keys', function () {
    $repo = new PostRepository;
    $post = $repo->latest(1)[0];

    expect($post)->toHaveKeys(['slug', 'title', 'excerpt', 'cover', 'published_at', 'html', 'tags', 'featured']);
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
        ->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});
