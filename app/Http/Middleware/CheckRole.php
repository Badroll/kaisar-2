<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route($role . '.login');
        }

        $user = Auth::user();

        // Pastikan user terautentikasi dan memiliki role yang sesuai
        if ($user && $user->hasRole($role)) {
            return $next($request);
        }

        // Jika user tidak memiliki role yang sesuai, arahkan ke halaman login role tersebut
        return redirect()->route($role . '.login');
    }
}
