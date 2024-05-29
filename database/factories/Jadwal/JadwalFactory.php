<?php

namespace Database\Factories\Jadwal;

use App\Models\Jadwal\Jadwal;
use App\Models\Jadwal\Lain;
use App\Models\Jadwal\Prodi;
use App\Models\Jadwal\TA;
use App\Models\Jadwal\TPB;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kelasDetailJadwal = fake()->randomElement([
            Prodi::class,
            TPB::class,
            TA::class,
            Lain::class,
        ]);

        return [
            'tanggal_mulai' => fake()->date(),
            'waktu_mulai' => fake()->time(),
            'waktu_selesai' => fake()->time(),
            'ruangan' => fake()->bothify('??-###'),
            'pengulangan' => fake()->randomElement([
                Jadwal::TANPA_PENGULANGAN,
                Jadwal::PENGULANGAN_BULANAN,
                Jadwal::PENGULANGAN_MINGGUAN,
                Jadwal::PENGULANGAN_TAHUNAN,
            ]),
            'id_detail_jadwal' => $kelasDetailJadwal::factory(),
            'kelas_detail_jadwal' => $kelasDetailJadwal,
            'user_id_pembuat' => User::first()?->getKey() ?: User::factory(),
        ];
    }
}
