<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Soal>
 */
class SoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kuis_id' => \App\Models\Kuis::factory(),
            'urutan' => 1,
            'tipe' => 'pilihan_ganda',
            'konten_soal' => fake()->sentence(),
            'gambar_soal' => null,
            'jumlah_pilihan' => 3,
            'jawaban_benar' => 1,
        ];
    }
}
