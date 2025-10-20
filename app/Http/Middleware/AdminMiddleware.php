<?php

// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        return $next($request);
    }
}
