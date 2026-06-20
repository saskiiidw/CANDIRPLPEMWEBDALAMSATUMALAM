<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (! $user->is_active) {
            abort(403, 'Akun Anda telah dinonaktifkan.');
        }

        // Penjual yang belum diverifikasi admin diarahkan ke halaman tunggu,
        // kecuali mereka memang sedang mengakses route seller.pending itu sendiri.
        if ($user->role === 'penjual' && ! $user->is_verified) {
            if (! $request->routeIs('seller.pending')) {
                return redirect()->route('seller.pending');
            }
        }

        return $next($request);
    }
}