<?php

use Inertia\Testing\AssertableInertia;

// Homepage
test('homepage returns 200 and renders Home component', function () {
    $this->get('/')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Home'));
});

// Software
test('software index returns 200 and renders Software component', function () {
    $this->get('/software')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Software'));
});

test('software case study returns 200 and renders SoftwareCase component', function () {
    $this->get('/software/hubspot-onboarding-sync')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('SoftwareCase'));
});

test('unknown software case study returns 404', function () {
    $this->get('/software/does-not-exist')->assertNotFound();
});

// Music
test('music index returns 200 and renders Music component', function () {
    $this->get('/music')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Music'));
});

test('music song returns 200 and renders Song component', function () {
    $this->get('/music/axolotl-waddle')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Song'));
});

test('unknown song returns 404', function () {
    $this->get('/music/does-not-exist')->assertNotFound();
});

// Photography
test('photography index returns 200 and renders Photography component', function () {
    $this->get('/photography')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Photography'));
});

test('photography gallery returns 200 and renders Gallery component', function () {
    $this->get('/photography/animals')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Gallery'));
});

test('unknown gallery returns 404', function () {
    $this->get('/photography/does-not-exist')->assertNotFound();
});

// Blog
test('blog index returns 200 and renders Blog component', function () {
    $this->get('/blog')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Blog'));
});

test('blog post returns 200 and renders Post component', function () {
    $this->get('/blog/10-essential-git-commands-every-developer-should-start-using')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Post'));
});

test('unknown blog post returns 404', function () {
    $this->get('/blog/does-not-exist')->assertNotFound();
});

// About
test('about returns 200 and renders About component', function () {
    $this->get('/about')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('About'));
});

// Contact
test('contact page returns 200 and renders Contact component', function () {
    $this->get('/contact')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Contact'));
});
