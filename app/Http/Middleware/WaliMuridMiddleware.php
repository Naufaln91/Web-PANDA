<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliMuridMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan wali murid.');
        }

        /** @var User $user */
        $user = Auth::user();
        if (!$user->isWaliMurid()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan wali murid.');
        }

        return $next($request);
    }
}
