<?php

use App\Support\SafeMarkdownRenderer;

test('safe markdown renderer preserves useful content and strips executable html', function () {
    $html = app(SafeMarkdownRenderer::class)->render(<<<'MARKDOWN'
# Safe heading

<script>alert('xss')</script>
<img src="/media/example.jpg" onerror="alert('xss')">
<a href="javascript:alert('xss')" onclick="alert('xss')">Unsafe link</a>
<a href="/about">Safe link</a>
<iframe srcdoc="<script>alert('xss')</script>"></iframe>
<div style="position:fixed" onmouseover="alert('xss')">Kept text</div>
MARKDOWN);

    expect($html)
        ->toContain('<h1>Safe heading</h1>')
        ->toContain('src="/media/example.jpg"')
        ->toContain('href="/about"')
        ->toContain('rel="noopener noreferrer"')
        ->toContain('Kept text')
        ->not->toContain('<script')
        ->not->toContain('<iframe')
        ->not->toContain('javascript:')
        ->not->toContain('onerror')
        ->not->toContain('onclick')
        ->not->toContain('onmouseover')
        ->not->toContain('style=');
});

test('safe markdown renderer preserves common article structures', function () {
    $html = app(SafeMarkdownRenderer::class)->render(<<<'MARKDOWN'
## Example

```php
echo 'safe';
```

| Name | Value |
| --- | --- |
| One | Two |

> A useful quote.
MARKDOWN);

    expect($html)
        ->toContain('<h2>Example</h2>')
        ->toContain('<pre><code class="language-php">')
        ->toContain('<table>')
        ->toContain('<blockquote>');
});
