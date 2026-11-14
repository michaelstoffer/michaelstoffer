---
slug: wakebot
published_at: "2026-11-14T11:20:02-05:00"
title: Wakebot — Community Automation for Nerrowake
summary: A TypeScript Discord bot built for Nerrowake, bringing moderation, member participation, scheduled tasks, and configurable community workflows into one application.
problem: Community management combines repeated staff actions, member interactions, and server-specific rules that need to stay understandable as features grow.
approach: Built an event-driven Discord application with slash commands, focused services, permission-aware interactions, and persistent per-server configuration through Prisma and MySQL.
result: A community bot with implemented moderation, welcome messages, self-service roles, leveling, audit logs, suggestion review, and database-backed deferred tasks.
cover: /media/software/wakebot/cover.webp
tags: [TypeScript, Discord.js, Prisma, MySQL, Automation]
featured: true
order: 2
stack: [TypeScript, Node.js, Discord.js, Prisma, MySQL, Zod, PM2]
links:
  - href: https://github.com/Nerrowake
    label: Nerrowake on GitHub
---

## Context

I built Wakebot for the Nerrowake community. It combines the routine work of running a Discord server with the interactions that give a community its identity: welcoming members, managing roles, recognizing participation, and giving suggestions a clear review process.

The project is a TypeScript application using Discord.js, Prisma, and MySQL. Its functionality is configured per Discord server, allowing the same application to use different channels, messages, and community settings without hardcoding each server's choices.

## Turning Staff Work into Clear Commands

Moderation needs more than a command that performs an action. Staff also need context, an appropriate response, and a record of what happened.

Wakebot implements warnings, timeouts, kicks, bans, temporary bans, and private moderator notes. Commands declare the Discord permissions they require and validate their interaction context. For example, the warning command checks that it is being used in a server, records the member and moderator against that server, and sends a moderation-log entry.

Automated moderation adds configurable checks for spam, invite links, excessive capitals, and banned words, with exemptions for selected roles and channels. These controls sit alongside staff commands rather than replacing the need for moderation decisions.

## Member Participation and Review Workflows

The same application supports the everyday member experience:

- Welcome messages use server-specific channels and configurable text.
- Reaction-role buttons let members choose from configured roles.
- Participation feeds XP, leveling, role rewards, and leaderboards.
- A starboard can surface messages that receive enough reactions.
- Suggestions move through a staff approve-or-deny interaction before approved items are presented for public voting.

The suggestion interaction checks the reviewer's permission and whether the item is still pending. That makes the state of the workflow explicit: an already-reviewed suggestion receives a different response from one waiting for a decision.

## Configuration and Persistence

Server configuration is stored by Discord guild ID. Channel choices, welcome text, role settings, and feature options can be updated through the application and retained in MySQL through Prisma.

That distinction matters in a long-running bot. A configurable message or moderation setting should not depend on a process-local variable that disappears when the application restarts.

Deferred tasks are also stored in the database with their server, task type, payload, and scheduled time. A polling service finds due work and dispatches it to a registered handler. This gives future-dated behavior an explicit record rather than relying only on an in-memory timer.

## Keeping the Implementation Understandable

The application runs as a single Node.js process. Discord event handlers route activity into focused services, while slash commands and button interactions handle their respective user flows. Shared configuration and persistence code support those features without requiring a separate service for every capability.

Zod validates environment settings at startup. Project documentation covers commands, configuration, leveling, architecture, database structure, and deployment with PM2. Those operational details are part of making the application maintainable alongside its visible features.

## What This Demonstrates

- Building event-driven applications around third-party APIs.
- Combining staff permissions, user feedback, and persistent workflow state.
- Separating per-server configuration from application code.
- Connecting community-facing features to reusable services and a relational data model.
- Documenting how an application is configured, deployed, and operated.

*Have a workflow that needs thoughtful automation? [Get in touch.](/contact)*
