<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hash existing unused OTP codes
        DB::table('otp_codes')
            ->where('is_used', false)
            ->get()
            ->each(function ($otp) {
                DB::table('otp_codes')
                    ->where('id', $otp->id)
                    ->update(['code' => Hash::make($otp->code)]);
            });
    }

    /**
     * Reverse the migrations.
     * Note: Hashing is one-way, so we cannot unhash. This migration is irreversible.
     */
    public function down(): void
    {
        // Cannot reverse hashing
    }
};
