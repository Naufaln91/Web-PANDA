<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'is_used',
        'resend_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public static function generateOtp($email)
    {
        // Hapus OTP lama yang belum digunakan
        self::where('email', $email)
            ->where('is_used', false)
            ->delete();

        // Generate OTP 6 digit
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $hashedCode = Hash::make($code);

        // Simpan OTP dengan expired 5 menit
        $otp = self::create([
            'email' => $email,
            'code' => $hashedCode,
            'expires_at' => Carbon::now()->addMinutes(5),
            'resend_count' => 0,
        ]);

        // Return OTP dengan plain code untuk email
        $otp->plain_code = $code;
        return $otp;
    }

    public static function verifyOtp($email, $code)
    {
        $otps = self::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->get();

        foreach ($otps as $otp) {
            if (Hash::check($code, $otp->code)) {
                $otp->update(['is_used' => true]);
                return true;
            }
        }

        return false;
    }

    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}
