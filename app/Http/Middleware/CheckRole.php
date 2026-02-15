<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login dulu.');
        }

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
