<?php

use Illuminate\Support\Facades\Vite;

test('x-frame-options denies framing', function () {
    $this->get('/')->assertHeader('X-Frame-Options', 'DENY');
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

test('content security policy is report only and uses a per-request nonce', function () {
    $response = $this->get('/');
    $policy = $response->headers->get('Content-Security-Policy-Report-Only');

    expect($policy)
        ->toContain("default-src 'self'")
        ->toContain("object-src 'none'")
        ->toContain("frame-ancestors 'none'")
        ->toContain("form-action 'self'")
        ->toContain("script-src 'self' 'nonce-".Vite::cspNonce()."' 'strict-dynamic'")
        ->toContain('report-uri '.route('csp.report'))
        ->toContain('report-to csp-endpoint');

    $response
        ->assertHeaderMissing('Content-Security-Policy')
        ->assertHeader('Reporting-Endpoints', 'csp-endpoint="'.route('csp.report').'"');
});

test('cross origin isolation headers are explicit and non-breaking', function () {
    $this->get('/')
        ->assertHeader('Cross-Origin-Opener-Policy', 'same-origin')
        ->assertHeader('Cross-Origin-Resource-Policy', 'same-site')
        ->assertHeader('Cross-Origin-Embedder-Policy', 'unsafe-none')
        ->assertHeader('Cross-Origin-Embedder-Policy-Report-Only', 'require-corp; report-to="csp-endpoint"');
});

test('content security policy can be promoted to enforcement through configuration', function () {
    config()->set('security.csp.report_only', false);

    $this->get('/')
        ->assertHeader('Content-Security-Policy')
        ->assertHeaderMissing('Content-Security-Policy-Report-Only');
});

test('all four security headers are present on every public route', function () {
    $routes = ['/', '/software', '/music', '/photography', '/blog', '/about', '/contact'];

    foreach ($routes as $route) {
        $this->get($route)
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }
});

test('security headers are present on 404 responses', function () {
    $this->get('/this-route-does-not-exist-anywhere')
        ->assertNotFound()
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeader('X-Content-Type-Options', 'nosniff');
});
