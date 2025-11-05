<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

        // Simpan OTP dengan expired 5 menit
        return self::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(5),
            'resend_count' => 0,
        ]);
    }

    public static function verifyOtp($email, $code)
    {
        $otp = self::where('email', $email)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otp) {
            $otp->update(['is_used' => true]);
            return true;
        }

        return false;
    }

    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}
