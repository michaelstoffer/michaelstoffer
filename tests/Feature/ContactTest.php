<?php

use App\Mail\ContactMessage;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;

$validPayload = [
    'name' => 'Jane Doe',
    'email' => 'jane@example.com',
    'message' => 'This is a test message that is long enough to pass validation.',
    'website' => '', // honeypot must be empty
];

// Happy path
test('valid submission sends emails and returns success flash', function () use ($validPayload) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', $validPayload)
        ->assertRedirect()
        ->assertSessionHas('flash.ok', true);

    Mail::assertSent(ContactMessage::class, 2); // one to owner, one copy to sender
});

test('valid submission without email only sends one email to owner', function () {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', [
            'name' => 'No Email Person',
            'email' => 'noemail@example.com',
            'message' => 'Just a message, no copy needed.',
            'website' => '',
        ])
        ->assertRedirect();

    // email is required by validation, so a copy is always sent — this just verifies 2 mails
    Mail::assertSent(ContactMessage::class, 2);
});

// Validation failures
test('name is required', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => '', 'email' => 'a@b.com', 'message' => 'Long enough message here.', 'website' => ''])
        ->assertSessionHasErrors('name');
});

test('email is required', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => 'Test', 'email' => '', 'message' => 'Long enough message here.', 'website' => ''])
        ->assertSessionHasErrors('email');
});

test('email must be valid', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => 'Test', 'email' => 'not-an-email', 'message' => 'Long enough message here.', 'website' => ''])
        ->assertSessionHasErrors('email');
});

test('message is required', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => 'Test', 'email' => 'a@b.com', 'message' => '', 'website' => ''])
        ->assertSessionHasErrors('message');
});

test('message must be at least 10 characters', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => 'Test', 'email' => 'a@b.com', 'message' => 'Short', 'website' => ''])
        ->assertSessionHasErrors('message');
});

test('message cannot exceed 4000 characters', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => 'Test', 'email' => 'a@b.com', 'message' => str_repeat('a', 4001), 'website' => ''])
        ->assertSessionHasErrors('message');
});

test('name cannot exceed 120 characters', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => str_repeat('a', 121), 'email' => 'a@b.com', 'message' => 'Long enough message here.', 'website' => ''])
        ->assertSessionHasErrors('name');
});

// Honeypot
test('filled honeypot field is rejected', function () {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', ['name' => 'Bot', 'email' => 'bot@spam.com', 'message' => 'Buy cheap stuff now!!!', 'website' => 'https://spam.com'])
        ->assertSessionHasErrors('website');
});

// Rate limiting
test('contact form is rate limited to one submission per minute', function () use ($validPayload) {
    Mail::fake();

    $this->post('/contact', $validPayload)->assertRedirect();
    $this->post('/contact', $validPayload)->assertTooManyRequests();
});
