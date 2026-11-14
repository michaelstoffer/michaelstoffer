---
slug: portfolio-reliability
published_at: "2026-11-14T11:01:32-05:00"
title: Keeping a Laravel Portfolio Reliable in Production
summary: Production maintenance on my own Laravel and Vue website, covering dependency security checks, deployment recovery, and honest contact-form failure handling.
problem: Passing application checks did not prevent a full server disk from blocking deployments, and contact submissions needed explicit handling when the mail transport failed.
approach: Recovered deployment capacity while preserving active application data, updated dependency safeguards, and added tested handling for owner-notification and sender-copy failures.
result: The latest application changes deployed successfully, public pages were verified, and contact-form transport failures now follow explicit, regression-tested error paths.
cover: /media/software/portfolio-reliability/cover.webp
tags: [Laravel, Vue 3, Testing, Deployment, Maintenance]
featured: false
stack: [Laravel, Vue 3, Inertia.js, Tailwind CSS, Pest, GitHub Actions, Laravel Forge]
links:
  - href: https://github.com/michaelstoffer/michaelstoffer
    label: View the website repository
---

## Context

My personal website is a production Laravel application with a Vue and Inertia frontend. It presents software work, writing, music, and photography, and gives visitors a way to contact me.

This case study covers a focused maintenance effort in November 2026. The work joined three concerns that are easy to treat separately: dependency security, deployment capacity, and what the application tells a visitor when an external service fails.

## Application Checks and Deployment Capacity

The immediate operational problem was a failed deployment. The server had no available disk space, so creating the next release failed before the application build could complete. Forge's site command runner also failed while trying to prepare its command files.

Application tests could not answer that problem on their own. I needed to inspect the host's actual filesystem state, identify the active release, and distinguish replaceable deployment copies from persistent application data.

I checked the active-release link and the shared storage paths before removing older releases. I preserved the running release and a recent rollback copy. I also removed the retired legacy website after confirming its files were separate from the live site's storage, while retaining the database.

With capacity restored, I redeployed the current application and checked the public site. The useful result was a working deployment path and a verified running application, rather than simply a successful cleanup command.

## Making Contact Failures Visible

The contact form depends on a mail transport. That creates two different failure cases: the notification to the site owner can fail, or the optional copy to the sender can fail after the owner notification succeeds.

I made those paths explicit:

- An owner-notification transport failure returns a validation-style error and does not set the success message.
- The sender copy is attempted only after the owner-notification send returns successfully.
- A sender-copy transport failure is logged without changing the successful owner-send result into an error.
- Error logging records the exception class without including the visitor's message body.

Regression tests simulate both transport failures and assert the response and success-message behavior. They test how the application handles the mail service; final inbox delivery remains a separate operational check.

## Dependency and Change Safeguards

I updated PHP dependencies to address reported security advisories and retained a repeatable review process for future changes. The repository has weekly dependency-update checks and a scheduled security-audit workflow for production Composer and npm dependencies.

Application-quality CI runs PHP tests, static analysis, and a production JavaScript build. These checks provide evidence about a proposed change before it is merged. The disk incident reinforced why that evidence needs to be paired with production checks after deployment.

## Result and Follow-Through

The maintenance work restored deployment capacity, delivered the current application changes, and added regression coverage for the contact form's external-service failure paths.

The work also produced concrete follow-up tasks. A GitHub-hosted public uptime check encountered Cloudflare's browser challenge, so I recorded that limitation rather than treating it as proof that the Laravel application was down. Disk alerts and off-server backups have separate acceptance criteria, including notification delivery and a restore test, before they can be counted as completed safeguards.

## What This Demonstrates

- Diagnosing a production failure beyond the application code.
- Preserving live data and rollback options during deployment recovery.
- Defining success and failure behavior around an external dependency.
- Using focused regression tests alongside deployment and public-page checks.
- Recording remaining operational work without overstating the result.

*Need help maintaining a Laravel application? [Get in touch.](/contact)*
