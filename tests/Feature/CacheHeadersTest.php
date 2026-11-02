<?php

test('anonymous content pages do not create sessions and require cache revalidation', function () {
    $response = $this->get('/');

    $response->assertOk()
        ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, public')
        ->assertHeaderMissing('Set-Cookie');

    expect($response->headers->get('Vary'))->toContain('X-Inertia');
});

test('inertia navigation responses are private and not stored', function () {
    $this->withHeader('X-Inertia', 'true')
        ->withHeader('X-Inertia-Version', '')
        ->get('/')
        ->assertOk()
        ->assertHeader('Cache-Control', 'no-store, private');
});

test('the contact form stays stateful and is not stored', function () {
    $this->get('/contact')
        ->assertOk()
        ->assertHeader('Cache-Control', 'no-store, private')
        ->assertHeader('Set-Cookie');
});
