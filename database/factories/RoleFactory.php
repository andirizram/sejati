<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->jobTitle(),
            'nama_unik' => implode('-', explode(' ', fake()->jobTitle())),
            'user_id_pembuat' => User::first()?->getKey() ?: User::factory(),
        ];
    }
}
