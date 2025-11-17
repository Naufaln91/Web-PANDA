<?php

namespace Database\Factories;

use App\Models\HistoriKuis;
use App\Models\User;
use App\Models\Kuis;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoriKuisFactory extends Factory
{
    protected $model = HistoriKuis::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'kuis_id' => Kuis::factory(),
            'jumlah_soal_dijawab' => $this->faker->numberBetween(1, 10),
            'jumlah_benar' => $this->faker->numberBetween(0, 10),
            'nilai' => $this->faker->numberBetween(0, 100),
            'detail_jawaban' => json_encode([
                ['soal_id' => 1, 'jawaban' => 'A', 'benar' => true],
                ['soal_id' => 2, 'jawaban' => 'B', 'benar' => false],
            ]),
            'waktu_selesai' => $this->faker->dateTime(),
        ];
    }
}
