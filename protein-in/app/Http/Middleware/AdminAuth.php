<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    private const SESSION_TTL_SECONDS = 20 * 60; // 20 minutes

    public function handle(Request $request, Closure $next): Response
    {
        $configured = config('app.admin_password');

        // Fail-secure: if no password is configured, deny all access
        if (!$configured) {
            return response(view('admin.login', ['error' => 'ADMIN_PASSWORD not set.', 'redirect' => $request->url()]), 403);
        }

        // Token in URL — authenticate and redirect to strip it from the URL
        $token = $request->query('token');
        if ($token) {
            if (hash_equals($configured, $token)) {
                $request->session()->put('admin_authed_at', now()->timestamp);
                // Redirect to the same URL without the token query param
                $clean = $request->fullUrlWithoutQuery('token');
                return redirect($clean);
            }
            // Bad token — fall through to normal login redirect
        }

        // Check active session and enforce 20-minute TTL
        $authedAt = $request->session()->get('admin_authed_at');
        if ($authedAt && (now()->timestamp - $authedAt) < self::SESSION_TTL_SECONDS) {
            // Refresh the TTL on activity
            $request->session()->put('admin_authed_at', now()->timestamp);
            return $next($request);
        }

        // Expired or no session
        $request->session()->forget('admin_authed_at');

        return redirect()->route('admin.login', ['redirect' => $request->url()]);
    }
}
