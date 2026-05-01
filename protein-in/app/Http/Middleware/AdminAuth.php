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
            return response(view('admin.login', ['error' => 'ADMIN_PASSWORD not set.']), 403);
        }

        if ($request->session()->get('admin_authed') === true) {
            return $next($request);
        }

        if ($request->isMethod('post') && $request->input('admin_password')) {
            if (hash_equals($configured, $request->input('admin_password'))) {
                $request->session()->put('admin_authed', true);
                return redirect($request->url());
            }
            return response(view('admin.login', ['error' => 'Wrong password.']), 401);
        }

        return response(view('admin.login', ['error' => null]), 401);
    }
}
