<?php

namespace Database\Factories\Jadwal;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TA>
 */
class TAFactory extends Factory
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
            'nama_mahasiswa' => fake()->name(),
            'dosen_pembimbing_1' => fake()->name(),
            'dosen_pembimbing_2' => fake()->name(),
            'judul' => fake()->jobTitle(),
            'dosen_penguji_1' => fake()->name(),
            'dosen_penguji_2' => fake()->name(),
            'tautan' => fake()->url(),
            'user_id_pembuat' => User::first()?->getKey() ?: User::factory(),
        ];
    }
}
