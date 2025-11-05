<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtpCode>
 */
class OtpCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'code' => str_pad(fake()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_used' => false,
            'resend_count' => 0,
        ];
    }

    public function used()
    {
        return $this->state(fn(array $attributes) => [
            'is_used' => true,
        ]);
    }

    public function expired()
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => Carbon::now()->subMinutes(1),
        ]);
    }
}
