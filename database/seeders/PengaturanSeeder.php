<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaturans = [
            [
                'key' => 'tanggal_kuliah_terakhir',
                'value' => '19700101',
                'description' => 'Nilai tanggal kuliah terakhir adalah nilai yang diberikan untuk membatasi perulangan pada jadwal yang memiliki perulangan. Format (yyyymmdd)'
            ],
        ];

        foreach ($pengaturans as $config) {
            Pengaturan::create($config);
        }
    }
}
