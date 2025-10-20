<?php

// database/migrations/2024_01_01_000001_create_whitelists_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whitelists', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_hp', 20)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whitelists');
    }
};
