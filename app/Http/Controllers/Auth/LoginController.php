<?php

// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Whitelist;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    // Login Admin dengan username & password
    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                $user->fill(['last_login' => now()]);
                $user->save();
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return redirect()->back()->with('error', 'Username atau password salah.');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    // Request OTP untuk Guru/Wali Murid
    public function requestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_hp' => 'required|string|regex:/^08[0-9]{9,11}$/',
        ], [
            'nomor_hp.required' => 'Nomor HP harus diisi.',
            'nomor_hp.regex' => 'Format nomor HP salah. Harus dimulai dengan 08 dan 11-13 digit.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('nomor_hp'),
            ]);
        }

        $nomorHp = $request->nomor_hp;

        // Cek apakah nomor HP di whitelist
        if (!Whitelist::isWhitelisted($nomorHp)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP tidak masuk whitelist.',
            ]);
        }

        // Generate OTP
        $otp = OtpCode::generateOtp($nomorHp);

        // Cek apakah user sudah ada
        $userExists = User::where('nomor_hp', $nomorHp)->exists();

        // Ambil role dari whitelist
        $whitelist = Whitelist::where('nomor_hp', $nomorHp)->first();
        $role = $whitelist ? $whitelist->role : null;

        return response()->json([
            'success' => true,
            'otp_code' => $otp->code, // Untuk development, tampilkan OTP
            'user_exists' => $userExists,
            'role' => $role,
            'message' => 'Kode OTP berhasil dikirim.',
        ]);
    }

    // Verify OTP dan Login/Register
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_hp' => 'required|string',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $nomorHp = $request->nomor_hp;
        $otpCode = $request->otp_code;

        // Verify OTP
        if (!OtpCode::verifyOtp($nomorHp, $otpCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau sudah kadaluarsa.',
            ]);
        }

        // Cek apakah user sudah ada
        $user = User::where('nomor_hp', $nomorHp)->first();

        if ($user) {
            // User sudah ada, langsung login
            Auth::login($user, true);
            $user->fill(['last_login' => now()]);
            $user->save();

            return response()->json([
                'success' => true,
                'is_new_user' => false,
                'redirect_url' => $this->getRedirectUrl($user),
            ]);
        }

        // User baru, perlu lengkapi profil
        return response()->json([
            'success' => true,
            'is_new_user' => true,
            'nomor_hp' => $nomorHp,
        ]);
    }

    // Complete Profile untuk user baru
    public function completeProfile(Request $request)
    {
        // Ambil role dari whitelist
        $whitelist = Whitelist::where('nomor_hp', $request->nomor_hp)->first();
        $role = $whitelist ? $whitelist->role : 'wali_murid'; // default wali_murid jika tidak ada

        if ($role === 'guru') {
            $validator = Validator::make($request->all(), [
                'nomor_hp' => 'required|string',
                'nama' => 'required|string|max:255',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'nomor_hp' => 'required|string',
                'nama_orangtua' => 'required|string|max:255',
                'nama_anak' => 'required|string|max:255',
                'kelas_anak' => 'required|string|max:50',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // Buat user baru
        if ($role === 'guru') {
            $user = User::create([
                'nomor_hp' => $request->nomor_hp,
                'nama' => $request->nama,
                'role' => $role,
                'last_login' => now(),
            ]);
        } else {
            $user = User::create([
                'nomor_hp' => $request->nomor_hp,
                'nama' => $request->nama_orangtua,
                'nama_anak' => $request->nama_anak,
                'kelas_anak' => $request->kelas_anak,
                'role' => $role,
                'last_login' => now(),
            ]);
        }

        // Login user
        Auth::login($user, true);

        return response()->json([
            'success' => true,
            'redirect_url' => $this->getRedirectUrl($user),
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Helper untuk redirect berdasarkan role
    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isGuru()) {
            return redirect()->route('guru.dashboard');
        } else {
            return redirect()->route('wali-murid.dashboard');
        }
    }

    private function getRedirectUrl($user)
    {
        if ($user->isAdmin()) {
            return route('admin.dashboard');
        } elseif ($user->isGuru()) {
            return route('guru.dashboard');
        } else {
            return route('wali-murid.dashboard');
        }
    }
}
