<?php

use Inertia\Testing\AssertableInertia;

test('shared site data is present on the homepage', function () {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page->has('site'));
});

test('shared site name is Michael Stoffer', function () {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('site.name', 'Michael Stoffer')
        );
});

test('shared site tagline is correct', function () {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('site.tagline', 'Engineer • Songwriter • Photographer')
        );
});

test('shared site data includes location email and url', function () {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('site.location')
            ->has('site.email')
            ->has('site.url')
        );
});

test('shared site data includes all three social links', function () {
    $this->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('site.socials.github')
            ->has('site.socials.linkedin')
            ->has('site.socials.x')
        );
});

test('shared site data is present on all public pages', function () {
    $routes = ['/', '/software', '/music', '/photography', '/blog', '/about', '/contact'];

    foreach ($routes as $route) {
        $this->get($route)
            ->assertInertia(fn (AssertableInertia $page) => $page->has('site'));
    }
});
