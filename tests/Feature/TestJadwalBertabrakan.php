<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestJadwalBertabrakan extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_jadwal_bertabrakan_menampilkan_semua_jadwal_dengan_tanggal_dan_waktu_mulai_yang_sama(): void
    {
        $jadwalAkanDiambil = 10;
        $user = User::factory()->create();
        $tanggalMulai = now()->toDateString();
        $waktuMulai = now()->toTimeString();

        for ($i = 0; $i < $jadwalAkanDiambil; $i++) {
            $lain = $user->jadwalLainDibuat()
                ->create([
                    'tipe' => $this->faker()->colorName(),
                    'dosen' => $this->faker()->name(),
                ]);

            $jadwal = $user->jadwalDibuat()
                ->make([
                    'tanggal_mulai' => $tanggalMulai,
                    'waktu_mulai' => $waktuMulai,
                    'waktu_selesai' => $waktuMulai,
                    'ruangan' => $this->faker()->bothify('??-###'),
                ]);

            $lain->jadwal()->save($jadwal);

            $user->jadwalDiambil()->attach($jadwal);
        }

        $jadwalBertabrakan = $user->jadwalBertabrakan;

        $this->assertNotEmpty($jadwalBertabrakan);
        $this->assertCount($jadwalAkanDiambil, $jadwalBertabrakan);
        $this->assertCount(1, $jadwalBertabrakan->pluck('id', 'tanggal_mulai'));
        $this->assertCount(1, $jadwalBertabrakan->pluck('id', 'waktu_mulai'));
    }
}
