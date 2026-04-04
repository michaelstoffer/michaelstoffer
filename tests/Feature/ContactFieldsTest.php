<?php

use App\Mail\ContactMessage;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;

$base = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'message' => 'This is a message long enough to pass validation.',
    'website' => '', // honeypot must be empty
];

// ── Topic field ───────────────────────────────────────────────────────────────

test('topic field is optional', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', $base)
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

test('topic field accepts a valid string', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', array_merge($base, ['topic' => 'Software Project']))
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

test('topic field cannot exceed 60 characters', function () use ($base) {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', array_merge($base, ['topic' => str_repeat('a', 61)]))
        ->assertSessionHasErrors('topic');
});

// ── Link field ────────────────────────────────────────────────────────────────

test('link field is optional', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', $base)
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

test('link field accepts a valid url', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', array_merge($base, ['link' => 'https://example.com/my-project']))
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

test('link field rejects a non-url string', function () use ($base) {
    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', array_merge($base, ['link' => 'not-a-url']))
        ->assertSessionHasErrors('link');
});

test('link field cannot exceed 500 characters', function () use ($base) {
    $longUrl = 'https://example.com/' . str_repeat('a', 490);

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', array_merge($base, ['link' => $longUrl]))
        ->assertSessionHasErrors('link');
});

// ── Flash message ─────────────────────────────────────────────────────────────

test('successful submission sets flash ok to true', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', $base)
        ->assertSessionHas('flash.ok', true);
});

test('successful submission sets flash msg', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', $base)
        ->assertSessionHas('flash.msg', "Thanks—your message is on its way.");
});

// ── Email recipients ──────────────────────────────────────────────────────────

test('owner receives the contact email', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', $base);

    Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
        return $mail->hasTo(config('mail.from.address')) && $mail->copy === false;
    });
});

test('submitter receives a copy of the email', function () use ($base) {
    Mail::fake();

    $this->withoutMiddleware(ThrottleRequests::class)
        ->post('/contact', $base);

    Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) use ($base) {
        return $mail->hasTo($base['email']) && $mail->copy === true;
    });
});

