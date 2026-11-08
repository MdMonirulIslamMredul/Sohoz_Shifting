<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureMarketingAttribution
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'ref'];

        foreach ($utmKeys as $key) {
            if ($request->filled($key)) {
                session([$key => $request->input($key)]);
            }
        }

        if (!session()->has('referrer_url') && $request->headers->has('referer')) {
            $referer = $request->headers->get('referer');
            $host = parse_url($referer, PHP_URL_HOST);
            if ($host && $host !== $request->getHost()) {
                session(['referrer_url' => $referer]);
            }
        }

        return $next($request);
    }
}

