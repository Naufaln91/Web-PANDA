<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $totalGuru = \App\Models\User::where('role', 'guru')->count();
        $totalWaliMurid = \App\Models\User::where('role', 'wali_murid')->count();
        $totalWhitelist = \App\Models\Whitelist::count();
        $totalKuis = \App\Models\Kuis::count();

        return view('admin.dashboard', compact('totalUsers', 'totalGuru', 'totalWaliMurid', 'totalWhitelist', 'totalKuis'));
    }

    public function guru()
    {
        $myKuis = \App\Models\Kuis::where('created_by', Auth::id())->count();
        $publishedKuis = \App\Models\Kuis::where('status', 'published')->count();

        return view('guru.dashboard', compact('myKuis', 'publishedKuis'));
    }

    public function waliMurid()
    {
        $publishedKuis = \App\Models\Kuis::where('status', 'published')->count();
        $user = Auth::user();

        return view('wali-murid.dashboard', compact('publishedKuis', 'user'));
    }
}
