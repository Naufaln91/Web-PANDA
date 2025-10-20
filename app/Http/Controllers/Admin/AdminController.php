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
            'nomor_hp' => 'required|string|regex:/^08[0-9]{9,11}$/|unique:whitelists,nomor_hp',
        ], [
            'nomor_hp.required' => 'Nomor HP harus diisi.',
            'nomor_hp.regex' => 'Format nomor HP salah.',
            'nomor_hp.unique' => 'Nomor HP ini sudah terdapat dalam whitelist.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('nomor_hp'),
            ]);
        }

        Whitelist::create(['nomor_hp' => $request->nomor_hp]);

        return response()->json([
            'success' => true,
            'message' => 'Nomor HP berhasil ditambah.',
        ]);
    }

    public function whitelistDestroy($id)
    {
        $whitelist = Whitelist::findOrFail($id);
        $nomorHp = $whitelist->nomor_hp;

        // Cek apakah ada user dengan nomor ini
        $user = User::where('nomor_hp', $nomorHp)->first();

        $message = 'Nomor HP berhasil dihapus.';

        if ($user) {
            $user->delete();
            $message = 'Nomor HP dan akun terkait berhasil dihapus.';
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
        $users = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.akun.index', compact('users'));
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
