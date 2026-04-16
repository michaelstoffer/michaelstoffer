---
slug: fieldops-hub
title: FieldOps Hub
summary: A full-stack SaaS platform that consolidates customer management, job scheduling, technician dispatch, and billing for small-to-medium field service businesses.
problem: Field service businesses like HVAC, plumbing, and electrical companies juggle customers, jobs, technicians, and invoices across disconnected tools — creating missed appointments, slow billing, and no visibility into the field.
approach: Built a multi-tenant Laravel 12 and Vue 3 application with real-time GPS tracking, a mobile-optimized progressive web app for technicians, automated email and SMS notifications, and an end-to-end estimate and invoicing workflow with Stripe payments.
result: A single platform that covers the full service lifecycle — from customer intake to job completion and payment — with role-based access, two-factor authentication, and reporting on job profitability and technician performance.
cover: /media/software/fieldops-hub/cover.jpg
tags: ["Laravel", "Vue 3", "Inertia.js", "SaaS", "Stripe", "Tailwind CSS"]
featured: true
order: 1
software_order: 1
stack:
  - PHP 8.4
  - Laravel 12
  - Vue 3 + TypeScript
  - Inertia.js
  - Tailwind CSS v4
  - Reka UI
  - Pest v4
  - Stripe
  - Twilio
  - SendGrid
  - Google Maps API
  - Vite 7
links:
  - href: https://github.com/michaelstoffer/fieldops-hub
    label: View on GitHub
---

FieldOps Hub started from a simple observation: most field service businesses are running their operations on a mix of spreadsheets, generic CRMs, and paper job sheets. Scheduling a job means checking one tool, dispatching a technician means calling or texting, and sending an invoice means switching to yet another app. Nothing talks to anything else.

The goal was to build one system that handles the complete job lifecycle — from the first customer call to the final payment — without requiring the business owner to glue together five different subscriptions.

## What It Does

**Customer and job management** sits at the core. Each customer can have multiple service locations, and jobs move through a defined lifecycle with status tracking at every step. The office can see everything; technicians see only their assigned work.

**Dispatch and field tools** are built around real-time GPS tracking and a mobile-optimized progressive web app. Technicians can check in, run through job checklists, capture notes, and mark work complete — all from their phone, with offline support for when they're in a basement or out of signal.

**Estimates and invoicing** support multi-tier packages so customers can choose their service level, accept estimates online, and pay invoices through Stripe. The billing flow was designed to minimize back-and-forth between the office and the customer.

**Notifications** keep everyone in the loop automatically. Appointment confirmations, job updates, and invoice reminders go out via email through SendGrid and SMS through Twilio — without anyone on the team having to remember to send them.

**Reporting** surfaces the numbers that actually matter for a service business: job profitability, technician utilization, revenue by period, and outstanding receivables.

## Technical Approach

The application is built on Laravel 12 with a Vue 3 and TypeScript frontend connected via Inertia.js. Multi-tenancy is handled at the data layer with scoped queries throughout, so each business's data stays isolated without a complex microservices setup.

Authentication uses Laravel Fortify with two-factor support. Role-based access control separates admin, dispatcher, and technician capabilities throughout the UI and at the API layer.

The progressive web app for technicians is built with offline-first patterns — service workers cache the relevant job data so the app stays functional even without a reliable connection.
