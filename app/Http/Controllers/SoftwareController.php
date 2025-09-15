<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SoftwareController extends Controller
{
    public function index()
    {
        // TODO: Replace with DB or Markdown-backed repository
        $services = [
            ['title' => 'Laravel + Vue Feature Work', 'desc' => 'Practical, well-tested builds with clear handoff.'],
            ['title' => 'Integrations', 'desc' => 'HubSpot, auth flows, webhooks, third-party APIs, and data syncs.'],
            ['title' => 'Reports & PDFs', 'desc' => 'Physician-ready PDFs, dashboards, exports, and templated documents.'],
            ['title' => 'Internal Tools', 'desc' => 'QA tooling, admin panels, queues, job orchestration, CI/CD hygiene.'],
        ];

        $caseStudies = config('case_studies');

        return Inertia::render('Software', compact('services', 'caseStudies'));
    }

    public function show(string $slug)
    {
        // TODO: Replace with repository lookup
        $map = config('case_studies');

        abort_unless($map[$slug]["slug"] === $slug, 404);
        $cs = $map[$slug];

        return Inertia::render('SoftwareCase', [
            'slug' => $slug,
            'case' => $cs,
        ]);
    }
}
