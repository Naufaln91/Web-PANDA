<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kuis>
 */
class KuisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => \App\Models\User::factory(),
            'judul' => fake()->sentence(),
            'deskripsi' => fake()->paragraph(),
            'waktu_tipe' => fake()->randomElement(['per_soal', 'keseluruhan', 'tanpa_waktu']),
            'durasi_waktu' => fake()->numberBetween(5, 3600),
            'status' => 'draft',
        ];
    }

    public function published()
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'published',
        ]);
    }
}
