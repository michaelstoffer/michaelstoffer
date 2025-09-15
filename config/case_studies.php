<?php

return [
    'clinical-pdf-reporting' => [
        'slug' => 'clinical-pdf-reporting',
        'title' => 'Clinical PDF Reporting Pipeline',
        'problem' => 'Inconsistent PDFs and manual edits slowed delivery.',
        'approach' => 'Blade templates, headless Chrome rendering, queue + retry, pixel-diff tests.',
        'result' => '70% faster turnaround; reproducible, versioned templates.',
        'metrics' => [
            ['label' => 'Turnaround', 'value' => '−70%'],
            ['label' => 'Manual Fixes', 'value' => '0 required'],
            ['label' => 'Uptime', 'value' => '99.9%'],
        ],
        'stack' => ['Laravel 11', 'Vue 3', 'Vite', 'Headless Chrome', 'S3'],
        'links' => [],
    ],
    'hubspot-onboarding-sync' => [
        'slug' => 'hubspot-onboarding-sync',
        'title' => 'HubSpot Onboarding Sync',
        'problem' => 'Sales → onboarding handoff broke under scale.',
        'approach' => 'Webhook handlers, nightly reconciliation, dead-letter queue, admin error inbox.',
        'result' => 'Near-zero drift; auditable state changes.',
        'metrics' => [
            ['label' => 'Data Drift', 'value' => '<0.5%'],
            ['label' => 'Time to Stage', 'value' => '−40%'],
        ],
        'stack' => ['Laravel', 'HubSpot API', 'MySQL', 'Redis'],
        'links' => [],
    ],
    'qa-internal-tooling' => [
        'slug' => 'qa-internal-tooling',
        'title' => 'QA Internal Tooling',
        'problem' => 'Reviewers context-switched across spreadsheets.',
        'approach' => 'Queues, hotkeys, RBAC, exports, audit trails.',
        'result' => '+35% throughput; cleaner handoffs.',
        'metrics' => [
            ['label' => 'Throughput', 'value' => '+35%'],
        ],
        'stack' => ['Vue 3', 'Inertia', 'Laravel', 'MySQL'],
        'links' => [],
    ],
];
