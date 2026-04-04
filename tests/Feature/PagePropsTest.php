<?php

use Inertia\Testing\AssertableInertia;

// ── Homepage ──────────────────────────────────────────────────────────────────

test('homepage passes caseStudies songs latestPosts and amr props', function () {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('caseStudies')
            ->has('songs')
            ->has('latestPosts')
            ->has('amr')
        );
});

test('homepage caseStudies are capped at 3', function () {
    $props = $this->get('/')->viewData('page')['props'];
    expect(count($props['caseStudies']))->toBeLessThan(4);
});

test('homepage latestPosts are capped at 3', function () {
    $props = $this->get('/')->viewData('page')['props'];
    expect(count($props['latestPosts']))->toBeLessThan(4);
});

test('homepage songs are capped at 3', function () {
    $props = $this->get('/')->viewData('page')['props'];
    expect(count($props['songs']))->toBeLessThan(4);
});

// ── Software ──────────────────────────────────────────────────────────────────

test('software index passes services and caseStudies', function () {
    $this->get('/software')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Software')
            ->has('services')
            ->has('caseStudies')
        );
});

test('software index services has exactly 4 entries', function () {
    $this->get('/software')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Software')
            ->has('services', 4)
        );
});

test('software case show passes slug and case props', function () {
    $this->get('/software/hubspot-onboarding-sync')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('SoftwareCase')
            ->has('slug')
            ->has('case')
            ->where('slug', 'hubspot-onboarding-sync')
        );
});

// ── Music ─────────────────────────────────────────────────────────────────────

test('music index passes songs and amr', function () {
    $this->get('/music')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Music')
            ->has('songs')
            ->has('amr')
        );
});

test('music song show passes slug song and related props', function () {
    $this->get('/music/axolotl-waddle')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Song')
            ->has('slug')
            ->has('song')
            ->has('related')
            ->where('slug', 'axolotl-waddle')
        );
});

test('music song related contains exactly 2 entries', function () {
    $this->get('/music/axolotl-waddle')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Song')
            ->has('related', 2)
        );
});

test('music song related does not include the current song', function () {
    $props = $this->get('/music/axolotl-waddle')->viewData('page')['props'];
    $slugs = array_column($props['related'], 'slug');

    expect($slugs)->not->toContain('axolotl-waddle');
});

// ── Photography ───────────────────────────────────────────────────────────────

test('photography index passes galleries', function () {
    $this->get('/photography')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Photography')
            ->has('galleries')
        );
});

test('photography gallery show passes slug gallery and related props', function () {
    $this->get('/photography/animals')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Gallery')
            ->has('slug')
            ->has('gallery')
            ->has('related')
            ->where('slug', 'animals')
        );
});

test('photography gallery related does not include the current gallery', function () {
    $response = $this->get('/photography/animals');
    $props = $response->viewData('page')['props'] ?? [];
    $related = $props['related'] ?? [];
    $slugs = array_column($related, 'slug');

    expect($slugs)->not->toContain('animals');
});

// ── Blog ──────────────────────────────────────────────────────────────────────

test('blog index passes posts', function () {
    $this->get('/blog')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog')
            ->has('posts')
        );
});

test('blog post show passes post prop', function () {
    $this->get('/blog/10-essential-git-commands-every-developer-should-start-using')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Post')
            ->has('post')
        );
});
