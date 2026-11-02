<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        Vite::useCspNonce();

        /** @var SymfonyResponse $response */
        $response = $next($request);

        $reportEndpoint = route('csp.report');
        $policy = implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'none'",
            "form-action 'self'",
            "script-src 'self' 'nonce-".Vite::cspNonce()."' 'strict-dynamic'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data:",
            "font-src 'self' https://fonts.gstatic.com data:",
            "media-src 'self'",
            "connect-src 'self'",
            "frame-src 'none'",
            "worker-src 'self' blob:",
            "manifest-src 'self'",
            "report-uri {$reportEndpoint}",
            'report-to csp-endpoint',
        ]);

        $response->headers->set(
            config('security.csp.report_only', true)
                ? 'Content-Security-Policy-Report-Only'
                : 'Content-Security-Policy',
            $policy,
        );
        $response->headers->set('Reporting-Endpoints', 'csp-endpoint="'.$reportEndpoint.'"');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-site');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');
        $response->headers->set('Cross-Origin-Embedder-Policy-Report-Only', 'require-corp; report-to="csp-endpoint"');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
