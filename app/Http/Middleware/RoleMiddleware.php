<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah user terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah user memiliki role yang sesuai
        if ($user->role && $user->role->role === $role) {
            return $next($request);
        }

        // Jika role tidak sesuai, kembalikan response 403 Forbidden
        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
