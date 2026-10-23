<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StealthShieldMiddleware
{
    /**
     * Handle an incoming request and apply stealth/anti-reconnaissance protections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $uri = strtolower($request->path());
        $ip = $request->ip();

        // 1. Immediately block probes for common sensitive files/endpoints
        $blockedProbes = [
            '.env', 'wp-login', 'wp-admin', 'phpmyadmin', 'pma',
            '.git', '.svn', 'config.json', 'composer.json', 'eval-stdin.php',
            'xmlrpc.php', 'setup.php', 'shell.php', 'adminer.php'
        ];

        foreach ($blockedProbes as $probe) {
            if (str_contains($uri, $probe)) {
                // Instantly lock IP for 24 hours
                Cache::put("admin_ip_blocked_{$ip}", true, now()->addHours(24));
                abort(404);
            }
        }

        $response = $next($request);

        // 2. Strip identifying server and tech stack headers
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
            @header_remove('Server');
        }

        if (method_exists($response, 'header')) {
            $response->headers->remove('X-Powered-By');
            $response->headers->remove('Server');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        }

        return $response;
    }
}
