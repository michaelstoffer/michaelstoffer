<?php

use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Validator;

// Helper: returns the ContactRequest validation rules
$rules = fn () => (new ContactRequest)->rules();

// Helper: builds a valid payload with optional overrides
$valid = fn (array $overrides = []) => array_merge([
    'name'    => 'Alice Smith',
    'email'   => 'alice@example.com',
    'topic'   => null,
    'message' => 'This is a perfectly valid message.',
    'link'    => null,
    'website' => '',
], $overrides);

// ── Name ──────────────────────────────────────────────────────────────────────

test('name is required', function () use ($rules, $valid) {
    $v = Validator::make($valid(['name' => '']), $rules());
    expect($v->errors()->has('name'))->toBeTrue();
});

test('name max 120 characters', function () use ($rules, $valid) {
    $v = Validator::make($valid(['name' => str_repeat('x', 121)]), $rules());
    expect($v->errors()->has('name'))->toBeTrue();
});

test('name of exactly 120 characters is accepted', function () use ($rules, $valid) {
    $v = Validator::make($valid(['name' => str_repeat('x', 120)]), $rules());
    expect($v->errors()->has('name'))->toBeFalse();
});

// ── Email ─────────────────────────────────────────────────────────────────────

test('email is required', function () use ($rules, $valid) {
    $v = Validator::make($valid(['email' => '']), $rules());
    expect($v->errors()->has('email'))->toBeTrue();
});

test('email must be a valid address', function () use ($rules, $valid) {
    $v = Validator::make($valid(['email' => 'not-an-email']), $rules());
    expect($v->errors()->has('email'))->toBeTrue();
});

test('email max 190 characters', function () use ($rules, $valid) {
    // 182 a's + '@test.com' (9 chars) = 191 chars, exceeds the 190 max
    $v = Validator::make($valid(['email' => str_repeat('a', 182) . '@test.com']), $rules());
    expect($v->errors()->has('email'))->toBeTrue();
});

// ── Topic ─────────────────────────────────────────────────────────────────────

test('topic is nullable', function () use ($rules, $valid) {
    $v = Validator::make($valid(['topic' => null]), $rules());
    expect($v->errors()->has('topic'))->toBeFalse();
});

test('topic accepts a valid string', function () use ($rules, $valid) {
    $v = Validator::make($valid(['topic' => 'Software Project']), $rules());
    expect($v->errors()->has('topic'))->toBeFalse();
});

test('topic max 60 characters', function () use ($rules, $valid) {
    $v = Validator::make($valid(['topic' => str_repeat('a', 61)]), $rules());
    expect($v->errors()->has('topic'))->toBeTrue();
});

test('topic of exactly 60 characters is accepted', function () use ($rules, $valid) {
    $v = Validator::make($valid(['topic' => str_repeat('a', 60)]), $rules());
    expect($v->errors()->has('topic'))->toBeFalse();
});

// ── Message ───────────────────────────────────────────────────────────────────

test('message is required', function () use ($rules, $valid) {
    $v = Validator::make($valid(['message' => '']), $rules());
    expect($v->errors()->has('message'))->toBeTrue();
});

test('message min 10 characters', function () use ($rules, $valid) {
    $v = Validator::make($valid(['message' => 'Short']), $rules());
    expect($v->errors()->has('message'))->toBeTrue();
});

test('message of exactly 10 characters is accepted', function () use ($rules, $valid) {
    $v = Validator::make($valid(['message' => str_repeat('a', 10)]), $rules());
    expect($v->errors()->has('message'))->toBeFalse();
});

test('message max 4000 characters', function () use ($rules, $valid) {
    $v = Validator::make($valid(['message' => str_repeat('a', 4001)]), $rules());
    expect($v->errors()->has('message'))->toBeTrue();
});

test('message of exactly 4000 characters is accepted', function () use ($rules, $valid) {
    $v = Validator::make($valid(['message' => str_repeat('a', 4000)]), $rules());
    expect($v->errors()->has('message'))->toBeFalse();
});

// ── Link ──────────────────────────────────────────────────────────────────────

test('link is nullable', function () use ($rules, $valid) {
    $v = Validator::make($valid(['link' => null]), $rules());
    expect($v->errors()->has('link'))->toBeFalse();
});

test('link must be a valid url when provided', function () use ($rules, $valid) {
    $v = Validator::make($valid(['link' => 'not-a-url']), $rules());
    expect($v->errors()->has('link'))->toBeTrue();
});

test('link accepts a valid https url', function () use ($rules, $valid) {
    $v = Validator::make($valid(['link' => 'https://example.com/project']), $rules());
    expect($v->errors()->has('link'))->toBeFalse();
});

test('link max 500 characters', function () use ($rules, $valid) {
    $longUrl = 'https://example.com/' . str_repeat('a', 490);
    $v = Validator::make($valid(['link' => $longUrl]), $rules());
    expect($v->errors()->has('link'))->toBeTrue();
});

// ── Honeypot ──────────────────────────────────────────────────────────────────

test('website honeypot must be empty string', function () use ($rules, $valid) {
    $v = Validator::make($valid(['website' => 'https://spam.com']), $rules());
    expect($v->errors()->has('website'))->toBeTrue();
});

test('website honeypot passes when empty', function () use ($rules, $valid) {
    $v = Validator::make($valid(['website' => '']), $rules());
    expect($v->errors()->has('website'))->toBeFalse();
});

test('honeypot error message says spam detected', function () {
    $messages = (new ContactRequest)->messages();
    expect($messages['website.size'])->toBe('Spam detected.');
});

// ── Full valid payload ────────────────────────────────────────────────────────

test('a complete valid payload passes all rules', function () use ($rules, $valid) {
    $payload = $valid([
        'topic' => 'Freelance Inquiry',
        'link'  => 'https://myportfolio.com',
    ]);

    $v = Validator::make($payload, $rules());
    expect($v->passes())->toBeTrue();
});
