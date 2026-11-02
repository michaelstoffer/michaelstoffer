---
slug: unsafe
title: Unsafe Song Fixture
description: Sanitizer regression fixture.
---
# Safe heading

<script>alert('xss')</script>
<img src="/media/example.jpg" onerror="alert('xss')">
<a href="javascript:alert('xss')">Unsafe link</a>
<div>Kept text</div>
