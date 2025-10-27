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
        Schema::table('whitelists', function (Blueprint $table) {
            $table->enum('role', ['guru', 'wali_murid'])->default('wali_murid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whitelists', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
