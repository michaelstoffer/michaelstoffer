<?php

// The LegacyRedirects middleware intercepts 404 responses for single-segment paths
// and redirects them to /blog/{slug} if a matching post file exists.

test('known post slug at root redirects to blog path', function () {
    // This slug exists in resources/content/blog/
    $this->get('/10-essential-git-commands-every-developer-should-start-using')
        ->assertRedirect('/blog/10-essential-git-commands-every-developer-should-start-using')
        ->assertStatus(301);
});

test('unknown single-segment path returns 404', function () {
    $this->get('/this-slug-does-not-exist-anywhere')->assertNotFound();
});

test('multi-segment unknown path returns 404 without redirect', function () {
    $this->get('/some/nested/unknown/path')->assertNotFound();
});

test('existing routes are not affected by legacy redirect middleware', function () {
    $this->get('/about')->assertOk();
    $this->get('/music')->assertOk();
    $this->get('/photography')->assertOk();
});
