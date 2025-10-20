<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
            $table->integer('urutan');
            $table->enum('tipe', ['pilihan_ganda', 'isian_singkat']);
            $table->text('konten_soal');
            $table->string('gambar_soal')->nullable();
            $table->integer('jumlah_pilihan')->nullable(); // untuk pilihan ganda
            $table->text('jawaban_benar'); // untuk isian singkat atau index pilihan benar
            $table->timestamps();

            $table->index('kuis_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal');
    }
};
