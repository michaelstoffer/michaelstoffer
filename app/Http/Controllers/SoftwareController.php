<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Support\CaseStudyRepository;

class SoftwareController extends Controller
{
    public function index(CaseStudyRepository $repo)
    {
        $services = [
            ['title' => 'Laravel + Vue Feature Work', 'desc' => 'Practical, well-tested builds with clear handoff.'],
            ['title' => 'Integrations', 'desc' => 'HubSpot, auth flows, webhooks, third-party APIs, and data syncs.'],
            ['title' => 'Reports & PDFs', 'desc' => 'Physician-ready PDFs, dashboards, exports, and templated documents.'],
            ['title' => 'Internal Tools', 'desc' => 'QA tooling, admin panels, queues, job orchestration, CI/CD hygiene.'],
        ];

        $caseStudies = $repo->all();
        return Inertia::render('Software', compact('services', 'caseStudies'));
    }

    public function show(string $slug, CaseStudyRepository $repo)
    {
        $cs = $repo->find($slug);
        return Inertia::render('SoftwareCase', ['slug' => $slug, 'case' => $cs]);
    }
}
