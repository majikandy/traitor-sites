<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $configured = config('app.admin_password');

        // Fail-secure: if no password is configured, deny all access
        if (!$configured) {
            return response(view('admin.login', ['error' => 'ADMIN_PASSWORD not set.', 'redirect' => $request->url()]), 403);
        }

        if ($request->session()->get('admin_authed') === true) {
            return $next($request);
        }

        return redirect()->route('admin.login', ['redirect' => $request->url()]);
    }
}
