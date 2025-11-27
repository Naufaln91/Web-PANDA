<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('otp_codes', function (Blueprint $table) {
            $table->renameColumn('nomor_hp', 'email');
            $table->string('email', 255)->change();
            $table->dropIndex(['nomor_hp']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_codes', function (Blueprint $table) {
            $table->renameColumn('email', 'nomor_hp');
            $table->string('nomor_hp', 20)->change();
            $table->dropIndex(['email']);
            $table->index('nomor_hp');
        });
    }
};
