<?php

use App\Mail\ContactMessage;

test('original message has correct subject', function () {
    $mail = new ContactMessage(['name' => 'Alice', 'email' => 'alice@example.com', 'message' => 'Hello']);

    expect($mail->subject)->toBe('New message via michaelstoffer.com');
});

test('copy message has correct subject', function () {
    $mail = new ContactMessage(['name' => 'Alice', 'email' => 'alice@example.com', 'message' => 'Hello'], true);

    expect($mail->subject)->toBe('Copy of your message to Michael Stoffer');
});

test('copy flag defaults to false', function () {
    $mail = new ContactMessage(['name' => 'Alice', 'email' => 'alice@example.com']);

    expect($mail->copy)->toBeFalse();
});

test('data is stored on the mailable', function () {
    $data = ['name' => 'Bob', 'email' => 'bob@example.com', 'message' => 'Test message'];
    $mail = new ContactMessage($data);

    expect($mail->data)->toBe($data);
});
