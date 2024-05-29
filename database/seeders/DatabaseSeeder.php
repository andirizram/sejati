<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->call([
                PermissionSeeder::class,
                RoleSeeder::class,
                UserSeeder::class,
                PengaturanSeeder::class,
//                MenuSeeder::class,
            ]);
        });
    }
}
