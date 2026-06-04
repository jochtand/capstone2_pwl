<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSession
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Session::has('user')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Session::get('user.role');

        if (!empty($roles) && !in_array($userRole, $roles)) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil response dari request yang sedang berjalan
        $response = $next($request);

        // Kembalikan response dengan header anti-cache untuk mencegah caching halaman
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}