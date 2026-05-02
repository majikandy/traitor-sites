<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    private const SESSION_TTL_SECONDS = 20 * 60; // 20 minutes
    private const TOKEN_WINDOW_SECONDS = 30;      // each HMAC token is valid for up to 2 windows (~60s)

    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('app.admin_shared_secret');

        // Fail-secure: if no secret is configured, deny all access
        if (!$secret) {
            abort(403, 'Admin access not configured.');
        }

        // HMAC token in URL — validate against current and previous 30s window,
        // then redirect to strip the token from the URL (keeps it out of browser history)
        $token = $request->query('token');
        if ($token) {
            if ($this->isValidHmacToken($token, $secret)) {
                $request->session()->put('admin_authed_at', now()->timestamp);
                return redirect($request->fullUrlWithoutQuery('token'));
            }
            // Bad/expired token — fall through to login redirect
        }

        // Check active session and enforce 20-minute TTL
        $authedAt = $request->session()->get('admin_authed_at');
        if ($authedAt && (now()->timestamp - $authedAt) < self::SESSION_TTL_SECONDS) {
            $request->session()->put('admin_authed_at', now()->timestamp); // slide TTL
            return $next($request);
        }

        $request->session()->forget('admin_authed_at');
        return redirect()->route('admin.login', ['redirect' => $request->url()]);
    }

    private function isValidHmacToken(string $token, string $secret): bool
    {
        $window = (int) floor(time() / self::TOKEN_WINDOW_SECONDS);
        foreach ([$window, $window - 1] as $w) {
            if (hash_equals(hash_hmac('sha256', (string) $w, $secret), $token)) {
                return true;
            }
        }
        return false;
    }
}
