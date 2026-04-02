<?php

test('non-production responses include noindex header', function () {
    // Test environment is never 'production', so the header should always be present
    $this->get('/')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

test('noindex header is present on all public routes', function () {
    $routes = ['/', '/software', '/music', '/photography', '/blog', '/about', '/contact'];

    foreach ($routes as $route) {
        $this->get($route)->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }
});
