<?php

// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Jika pengguna tidak login atau perannya tidak sesuai,
            // arahkan ke halaman yang tidak diizinkan.
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        return $next($request);
    }
}
