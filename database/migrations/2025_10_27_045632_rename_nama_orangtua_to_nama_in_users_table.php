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
        Schema::table('users', function (Blueprint $table) {
            // The column is already 'nama' in the original migration, no need to rename
            $table->string('nama_anak')->nullable()->change();
            $table->string('kelas_anak')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the nullable changes
            $table->string('nama_anak')->nullable(false)->change();
            $table->string('kelas_anak')->nullable(false)->change();
        });
    }
};
