<?php

// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Whitelist;
use App\Models\OtpCode;
use App\Mail\OtpEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        // Check if rate limited
        if ($this->isRateLimited($request)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            return redirect()->back()->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.');
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                // Clear rate limiter on successful login
                RateLimiter::clear($this->throttleKey($request));

                $user->fill(['last_login' => now()]);
                $user->save();
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return redirect()->back()->with('error', 'Username atau password salah.');
        }

        // Increment failed attempts
        RateLimiter::hit($this->throttleKey($request));

        // Log failed attempt
        Log::warning('Failed admin login attempt', [
            'username' => $request->username,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'attempts' => RateLimiter::attempts($this->throttleKey($request))
        ]);

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    // Request OTP untuk Guru/Wali Murid
    public function requestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email salah.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('email'),
            ]);
        }

        $email = $request->email;

        // Cek apakah email di whitelist
        if (!Whitelist::isWhitelisted($email)) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak masuk whitelist.',
            ]);
        }

        // Generate OTP
        $otp = OtpCode::generateOtp($email);

        // Kirim email OTP
        try {
            Mail::to($email)->send(new OtpEmail($otp->plain_code));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP. Silakan coba lagi.',
            ]);
        }

        // Cek apakah user sudah ada
        $userExists = User::where('email', $email)->exists();

        // Ambil role dari whitelist
        $whitelist = Whitelist::where('email', $email)->first();
        $role = $whitelist ? $whitelist->role : null;

        return response()->json([
            'success' => true,
            'user_exists' => $userExists,
            'role' => $role,
            'message' => 'Kode OTP berhasil dikirim ke email Anda.',
        ]);
    }

    // Verify OTP dan Login/Register
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $email = $request->email;
        $otpCode = $request->otp_code;

        // Verify OTP
        if (!OtpCode::verifyOtp($email, $otpCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau sudah kadaluarsa.',
            ]);
        }

        // Cek apakah user sudah ada
        $user = User::where('email', $email)->first();

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
            'email' => $email,
        ]);
    }

    // Complete Profile untuk user baru
    public function completeProfile(Request $request)
    {
        // Ambil role dari whitelist
        $whitelist = Whitelist::where('email', $request->email)->first();
        $role = $whitelist ? $whitelist->role : 'wali_murid'; // default wali_murid jika tidak ada

        if ($role === 'guru') {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'nama' => 'required|string|max:255',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
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
                'email' => $request->email,
                'nama' => $request->nama,
                'role' => $role,
                'last_login' => now(),
            ]);
        } else {
            $user = User::create([
                'email' => $request->email,
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

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('email'),
            ]);
        }

        $email = $request->email;

        // Cek apakah ada OTP yang masih aktif
        $otp = OtpCode::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada OTP aktif. Silakan minta OTP baru.',
            ]);
        }

        // Cek batas resend (maksimal 3 kali)
        if ($otp->resend_count >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Batas pengiriman ulang OTP telah tercapai. Silakan minta OTP baru.',
            ]);
        }

        // Increment resend count
        $otp->increment('resend_count');

        // Kirim ulang email OTP
        try {
            Mail::to($email)->send(new OtpEmail($otp->plain_code));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim ulang email OTP. Silakan coba lagi.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil dikirim ulang ke email Anda.',
            'remaining_resend' => 3 - $otp->resend_count,
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

    /**
     * Check if the login request is rate limited.
     */
    private function isRateLimited(Request $request): bool
    {
        return RateLimiter::tooManyAttempts($this->throttleKey($request), 5);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->string('username')) . '|' . $request->ip());
    }
}
