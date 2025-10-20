<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'nomor_hp',
        'code',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public static function generateOtp($nomorHp)
    {
        // Hapus OTP lama yang belum digunakan
        self::where('nomor_hp', $nomorHp)
            ->where('is_used', false)
            ->delete();

        // Generate OTP 6 digit
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP dengan expired 5 menit
        return self::create([
            'nomor_hp' => $nomorHp,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
    }

    public static function verifyOtp($nomorHp, $code)
    {
        $otp = self::where('nomor_hp', $nomorHp)
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
