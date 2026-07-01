<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * Usage: Route::middleware('role:administrator') or 'role:dosen,mahasiswa'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->role) {
            abort(403, 'Akses ditolak.');
        }

        foreach ($roles as $role) {
            if ($user->role->nama_role === $role) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak.');
    }
}