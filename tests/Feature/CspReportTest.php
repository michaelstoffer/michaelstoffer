<?php

use Illuminate\Support\Facades\Log;

test('legacy csp violation reports are accepted without csrf tokens', function () {
    Log::spy();

    $payload = json_encode([
        'csp-report' => [
            'document-uri' => 'https://michaelstoffer.com/',
            'blocked-uri' => 'https://example.com/tracker.js',
            'effective-directive' => 'script-src-elem',
            'source-file' => 'https://example.com/tracker.js',
            'line-number' => 1,
        ],
    ], JSON_THROW_ON_ERROR);

    $this->call(
        'POST',
        '/csp-report',
        server: ['CONTENT_TYPE' => 'application/csp-report'],
        content: $payload,
    )->assertNoContent();

    Log::shouldHaveReceived('notice')
        ->once()
        ->with('Browser security policy violation', Mockery::on(
            fn (array $report): bool => $report['blocked-uri'] === 'https://example.com/tracker.js'
                && $report['effective-directive'] === 'script-src-elem',
        ));
});

test('reporting api batches are accepted', function () {
    $payload = json_encode([[
        'type' => 'csp-violation',
        'body' => [
            'documentURL' => 'https://michaelstoffer.com/',
            'blockedURL' => 'https://example.com/script.js',
            'effectiveDirective' => 'script-src-elem',
        ],
    ]], JSON_THROW_ON_ERROR);

    $this->call(
        'POST',
        '/csp-report',
        server: ['CONTENT_TYPE' => 'application/reports+json'],
        content: $payload,
    )->assertNoContent();
});

test('oversized browser policy reports are rejected', function () {
    $this->call(
        'POST',
        '/csp-report',
        server: ['CONTENT_TYPE' => 'application/csp-report'],
        content: str_repeat('x', 65_537),
    )->assertStatus(413);
});

test('malformed browser policy reports are ignored safely', function () {
    $this->call(
        'POST',
        '/csp-report',
        server: ['CONTENT_TYPE' => 'application/csp-report'],
        content: '{invalid',
    )->assertNoContent();
});
