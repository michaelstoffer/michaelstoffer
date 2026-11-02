---
title: Unsafe Post Fixture
excerpt: Sanitizer regression fixture.
published_at: 2020-01-01 08:00:00
status: published
tags: [Security]
---
# Safe heading

<script>alert('xss')</script>
<img src="/media/example.jpg" onerror="alert('xss')">
<a href="javascript:alert('xss')" onclick="alert('xss')">Unsafe link</a>
<a href="/about">Safe link</a>
<iframe srcdoc="<script>alert('xss')</script>"></iframe>
<div style="position:fixed">Kept text</div>
