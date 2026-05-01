<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('admin_authed')) {
            return $next($request);
        }

        if ($request->isMethod('post') && $request->input('admin_password')) {
            if ($request->input('admin_password') === config('app.admin_password')) {
                $request->session()->put('admin_authed', true);
                return redirect($request->url());
            }
            return response(view('admin.login', ['error' => 'Wrong password.']), 401);
        }

        return response(view('admin.login', ['error' => null]), 401);
    }
}
