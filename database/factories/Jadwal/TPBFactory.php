<?php

namespace Database\Factories\Jadwal;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TPB>
 */
class TPBFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipe' => fake()->colorName(),
            'mata_kuliah' => fake()->jobTitle(),
            'sks' => fake()->numberBetween(1, 3),
            'semester' => fake()->numberBetween(1, 8),
            'dosen' => fake()->name(),
            'description' => null,
            'user_id_pembuat' => User::first()?->getKey() ?: User::factory(),
        ];
    }
}
