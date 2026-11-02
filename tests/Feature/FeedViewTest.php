<?php

test('rss descriptions cannot break out into executable markup', function () {
    $xml = view('feed.rss', [
        'posts' => [[
            'title' => 'Unsafe feed item',
            'slug' => 'unsafe-feed-item',
            'published_at' => '2026-01-01T12:00:00+00:00',
            'excerpt' => 'Break ]]> <script>alert("xss")</script>',
        ]],
    ])->render();

    expect($xml)
        ->toContain('Break ]]&gt; &lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;')
        ->not->toContain('<![CDATA[')
        ->not->toContain('<script>');
});
