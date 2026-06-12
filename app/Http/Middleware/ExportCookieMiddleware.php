<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportCookieMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response && $response->headers && $response->headers->has('Content-Disposition')) {
            $disposition = $response->headers->get('Content-Disposition');
            if (str_contains($disposition, 'attachment')) {
                // Set cookie so JavaScript can detect when download completes
                // Name: export_done, Value: 1, Minutes: 1, Path: '/', Domain: null, Secure: false, HttpOnly: false
                $cookie = cookie('export_done', '1', 1, '/', null, false, false);
                $response->headers->setCookie($cookie);
            }
        }

        return $response;
    }
}
