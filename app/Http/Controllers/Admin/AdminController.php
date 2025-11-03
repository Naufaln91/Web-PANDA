<?php

// app/Http/Controllers/Admin/AdminController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Whitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalWaliMurid = User::where('role', 'wali_murid')->count();
        $totalWhitelist = Whitelist::count();

        return view('admin.dashboard', compact('totalUsers', 'totalGuru', 'totalWaliMurid', 'totalWhitelist'));
    }

    // Kelola Whitelist
    public function whitelistIndex()
    {
        $whitelists = Whitelist::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.whitelist.index', compact('whitelists'));
    }

    public function whitelistStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:whitelists,email',
            'role' => 'required|in:guru,wali_murid',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email salah.',
            'email.unique' => 'Email ini sudah terdapat dalam whitelist.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        Whitelist::create([
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil ditambah.',
        ]);
    }

    public function whitelistDestroy($id)
    {
        $whitelist = Whitelist::findOrFail($id);
        $email = $whitelist->email;

        // Cek apakah ada user dengan email ini
        $user = User::where('email', $email)->first();

        $message = 'Email berhasil dihapus.';

        if ($user) {
            $user->delete();
            $message = 'Email dan akun terkait berhasil dihapus.';
        }

        $whitelist->delete();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    // Kelola Akun
    public function akunIndex()
    {
        $guru = User::where('role', 'guru')
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'guru_page');

        $waliMurid = User::where('role', 'wali_murid')
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'wali_murid_page');

        return view('admin.akun.index', compact('guru', 'waliMurid'));
    }

    public function akunDestroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun admin.',
            ]);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dihapus.',
        ]);
    }
}
