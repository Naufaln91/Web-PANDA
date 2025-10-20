<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pilihan_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
            $table->integer('urutan');
            $table->text('konten_pilihan');
            $table->string('gambar_pilihan')->nullable();
            $table->boolean('is_benar')->default(false);
            $table->timestamps();

            $table->index('soal_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pilihan_jawaban');
    }
};
