<?php

test('x-frame-options is set to SAMEORIGIN', function () {
    $this->get('/')->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});

test('x-content-type-options is set to nosniff', function () {
    $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff');
});

test('referrer-policy is strict-origin-when-cross-origin', function () {
    $this->get('/')->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('permissions-policy restricts camera microphone and geolocation', function () {
    $this->get('/')->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
});

test('all four security headers are present on every public route', function () {
    $routes = ['/', '/software', '/music', '/photography', '/blog', '/about', '/contact'];

    foreach ($routes as $route) {
        $this->get($route)
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }
});

test('security headers are present on 404 responses', function () {
    $this->get('/this-route-does-not-exist-anywhere')
        ->assertNotFound()
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('X-Content-Type-Options', 'nosniff');
});
