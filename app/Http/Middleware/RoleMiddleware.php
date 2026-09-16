<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki hak akses untuk fitur ini.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki wewenang untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}
