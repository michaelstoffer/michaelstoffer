<?php

use App\Support\MusicRepository;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    Cache::forget('music:all');
    Cache::forget('music:axolotl-waddle');
    Cache::forget('music:all:v2');
    Cache::forget('music:axolotl-waddle:v2');
    Cache::forget('music:unsafe:v2');
});

test('all returns an array of songs', function () {
    $repo = new MusicRepository;

    $songs = $repo->all();

    expect($songs)->toBeArray()->not->toBeEmpty();
});

test('each song has required keys', function () {
    $repo = new MusicRepository;
    $song = $repo->all()[0];

    expect($song)->toHaveKeys(['slug', 'title', 'description', 'duration', 'audioSrc', 'cover', 'tags', 'featured']);
});

test('songs are sorted alphabetically by title', function () {
    $repo = new MusicRepository;
    $titles = array_column($repo->all(), 'title');
    $sorted = $titles;
    usort($sorted, 'strcasecmp');

    expect($titles)->toBe($sorted);
});

test('find returns a song by slug', function () {
    $repo = new MusicRepository;
    $song = $repo->find('axolotl-waddle');

    expect($song['title'])->toBe('The Axolotl Waddle')
        ->and($song)->toHaveKey('html')
        ->and($song)->not->toHaveKey('body');
});

test('find caches the result', function () {
    $repo = new MusicRepository;
    $repo->find('axolotl-waddle');

    expect(Cache::has('music:axolotl-waddle:v2'))->toBeTrue();
});

test('song html is rendered and sanitized on the server', function () {
    $repo = new MusicRepository(base_path('tests/Fixtures/music'));
    $song = $repo->find('unsafe');

    expect($song['html'])
        ->toContain('<h1>Safe heading</h1>')
        ->toContain('Kept text')
        ->not->toContain('<script')
        ->not->toContain('javascript:')
        ->not->toContain('onerror');
});

test('find throws 404 for unknown slug', function () {
    $repo = new MusicRepository;

    expect(fn () => $repo->find('this-song-does-not-exist'))
        ->toThrow(HttpException::class);
});
