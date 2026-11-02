---
slug: unsafe
title: Unsafe Case Study Fixture
summary: Sanitizer regression fixture.
---
# Safe heading

<script>alert('xss')</script>
<a href="/about" onclick="alert('xss')">Safe destination</a>
<iframe src="https://example.com"></iframe>
<div>Kept text</div>
