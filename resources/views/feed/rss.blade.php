<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
    <channel>
        <title>Michael Stoffer — Blog</title>
        <link>{{ config('app.url') }}</link>
        <description>Practical notes on building, learning, and shipping.</description>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        @foreach($posts as $p)
            <item>
                <title>{{ $p['title'] }}</title>
                <link>{{ config('app.url') }}/blog/{{ $p['slug'] }}</link>
                <guid isPermaLink="true">{{ config('app.url') }}/blog/{{ $p['slug'] }}</guid>
                <pubDate>{{ \Carbon\Carbon::parse($p['published_at'])->toRfc2822String() }}</pubDate>
                <description><![CDATA[{!! $p['excerpt'] ?? Str::limit(strip_tags($p['body'] ?? ''), 300) !!}]]></description>
            </item>
        @endforeach
    </channel>
</rss>
