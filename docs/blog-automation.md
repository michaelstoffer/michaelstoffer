# Blog automation policy

The blog automation prepares a complete pull request. It must never merge, deploy, or publish directly.

## Cadence

- Prepare one draft every two weeks.
- Do not open a new draft while another automation-created blog pull request remains open.
- Prefer quality and relevance over filling a calendar slot.

## Editorial focus

Prioritize original, useful work in this order:

1. Laravel, PHP, Vue, Inertia, application security, performance, testing, and maintainability.
2. Practical engineering lessons that can be demonstrated without exposing client or employer information.
3. Occasional thoughtful connections between engineering and Michael's creative work.

Do not produce generic search-engine filler, rewrite release notes, invent personal experience, invent project outcomes, imply client endorsement, or publish unverifiable claims.

## Required sources and review

- Use current primary sources such as official framework documentation, release notes, standards, and original research.
- Link sources naturally where they support time-sensitive or technical claims.
- Verify every code sample against the repository's supported versions.
- Run `php artisan blog:validate` and the focused blog tests before opening a pull request.
- The pull request description must include the topic rationale, source list, validation results, and any claims that need Michael's judgment.

## Front matter

```yaml
---
title: "Clear, specific title"
excerpt: A concise description written for people, not search engines.
cover: /media/blog/example-slug/cover.webp
published_at: "2026-11-17 08:00:00 -0500"
modified_at: "2026-11-17 08:00:00 -0500"
status: published
tags: ["Laravel", "PHP", "Security"]
---
```

`status: draft` and future publication timestamps are not visible publicly. A reviewed pull request may use `status: published`; merging it is the explicit publication approval.

## Cover images

- Use an original, license-safe cover image.
- Avoid screenshots containing secrets, client data, copyrighted UI, or invented product claims.
- Generate responsive WebP variants matching the repository's existing naming convention.
