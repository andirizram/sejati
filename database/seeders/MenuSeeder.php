<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            'pengelolaan-akun' => 'Pengelolaan Akun',
            'jadwal-saya' => 'Jadwal Saya',
            'jadwal-prodi' => 'Jadwal Prodi',
            'jadwal-ta' => 'Jadwal TA',
            'jadwal-tpb' => 'Jadwal TPB',
            'jadwal-lain' => 'Jadwal Lain',
            'pengajuan-perubahan-jadwal' => 'Pengajuan Perubahan Jadwal',
        ];

        foreach ($menus as $namaUnik => $nama) {
            if (! Menu::where('nama_unik', $namaUnik)->exists()) {
                Menu::factory()->create([
                    'nama' => $nama,
                    'nama_unik' => $namaUnik,
                ]);
            }
        }
    }
}
