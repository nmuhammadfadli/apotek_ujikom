<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Usage: middleware('role:owner,pegawai') or middleware('role:owner')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            // not authenticated
            return redirect()->route('login')->with('error', 'Anda harus login dulu.');
        }

        // roles parameter mungkin dikirim sebagai single string with comma if older laravel,
        // but variadic ...$roles already splits arguments. If one string contains commas, split it:
        $allowed = [];
        foreach ($roles as $r) {
            $allowed = array_merge($allowed, array_map('trim', explode(',', $r)));
        }

        if (!in_array($user->role, $allowed)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
