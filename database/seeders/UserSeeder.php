<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = [
            'app.admin@local.dev',
            'akun.admin@local.dev',
            'andika.120140002@student.itera.ac.id',
            'duta.12013015@student.itera.ac.id',
            'andika.setiawan@if.itera.ac.id',
            'setiabudi.wira@tse.itera.ac.id',
            'andika.118140123@student.itera.ac.id',
        ];

        foreach ($emails as $email) {
            if (!User::where('email', $email)->exists()) {
                $user = User::factory()->create([
                    'email' => $email,
                ]);

                $user->assignRole('Administrator');
            }
        }
    }
}
