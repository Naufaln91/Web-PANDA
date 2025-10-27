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
            $table->renameColumn('nama_orangtua', 'nama');
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
            $table->renameColumn('nama', 'nama_orangtua');
            $table->string('nama_anak')->nullable(false)->change();
            $table->string('kelas_anak')->nullable(false)->change();
        });
    }
};
