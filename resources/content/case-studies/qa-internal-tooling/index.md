---
title: QA Internal Tooling
summary: Focused app for reviewers with queues, shortcuts, and exports.
problem: "Reviewers context-switched across spreadsheets."
approach: "Queues, hotkeys, RBAC, exports, audit trails."
result: "+35% throughput; cleaner handoffs."
metrics:
  - { label: Throughput, value: "+35%" }
stack: [Vue 3, Inertia, Laravel, MySQL]
links: []
tags: [Vue 3, RBAC, Auditing]
cover: /media/software/qa-internal-tooling/cover.jpg
---

The QA team was managing their review queue across a mix of spreadsheets and email threads. Every context switch—open spreadsheet, find the next item, copy an ID, switch back to the review tool—added friction that compounded across hundreds of reviews per day. Handoffs between shifts were inconsistent and audit trails were essentially nonexistent.

The replacement was a purpose-built queue application with a single-screen review interface. Keyboard shortcuts let reviewers move through items without touching the mouse. Role-based access control scoped what each reviewer could see and action, and every state transition was logged with a user, timestamp, and reason. Exports were available in structured formats so downstream reporting didn't require manual copying.

Throughput increased 35% in the first month, measured against the same team size and volume. More importantly, handoffs between shifts became reliable—the next reviewer could pick up exactly where the last one left off, with full context and no ambiguity about what had already been actioned.
