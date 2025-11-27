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
        Schema::create('histori_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
            $table->integer('jumlah_soal_dijawab');
            $table->integer('jumlah_benar');
            $table->integer('nilai');
            $table->json('detail_jawaban')->nullable(); // menyimpan detail jawaban per soal
            $table->timestamp('waktu_selesai');
            $table->timestamps();

            $table->index(['user_id', 'kuis_id']);
            $table->index('waktu_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histori_kuis');
    }
};
