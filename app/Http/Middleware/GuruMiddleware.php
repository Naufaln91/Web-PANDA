<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        /** @var User $user */
        $user = Auth::user();
        if (!$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        return $next($request);
    }
}
