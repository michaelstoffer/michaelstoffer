<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CspReportController extends Controller
{
    private const MAX_REPORT_BYTES = 65_536;

    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();

        abort_if(strlen($content) > self::MAX_REPORT_BYTES, 413);

        $decoded = json_decode($content, true);

        if (is_array($decoded)) {
            foreach ($this->reportsFrom($decoded) as $report) {
                Log::notice('Browser security policy violation', $report);
            }
        }

        return response()->noContent();
    }

    /** @return array<int, array<string, mixed>> */
    private function reportsFrom(array $payload): array
    {
        $reports = array_is_list($payload) ? $payload : [$payload];

        return collect($reports)
            ->map(function (mixed $report): array {
                if (! is_array($report)) {
                    return [];
                }

                $body = $report['csp-report'] ?? $report['body'] ?? $report;

                if (! is_array($body)) {
                    return [];
                }

                return collect($body)
                    ->only([
                        'document-uri',
                        'documentURL',
                        'blocked-uri',
                        'blockedURL',
                        'effective-directive',
                        'effectiveDirective',
                        'violated-directive',
                        'source-file',
                        'sourceFile',
                        'line-number',
                        'lineNumber',
                        'column-number',
                        'columnNumber',
                        'disposition',
                        'status-code',
                        'statusCode',
                    ])
                    ->all();
            })
            ->filter()
            ->values()
            ->all();
    }
}
