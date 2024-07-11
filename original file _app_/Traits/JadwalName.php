<?php

namespace App\Traits;

trait JadwalName
{
    public function name($kelasDetailJadwal, $detailJadwal)
    {
        // Initialize name as empty string to handle cases where detailJadwal is null
        $name = '';

        if ($detailJadwal !== null) {
            switch ($kelasDetailJadwal) {
                case 'App\Models\Jadwal\Prodi':
                    // Include 'kelas' in the name if available
                    $name = $detailJadwal->mata_kuliah . ' (' . $detailJadwal->tipe;
                    if (!empty($detailJadwal->kelas)) {
                        $name .= ') (' . $detailJadwal->kelas;
                    }
                    $name .= ')';
                    break;
                case 'App\Models\Jadwal\TPB':
                    // Include 'kelas' in the name if available
                    $name = $detailJadwal->mata_kuliah . ' (' . $detailJadwal->tipe;
                    if (!empty($detailJadwal->kelas)) {
                        $name .= ') (' . $detailJadwal->kelas;
                    }
                    $name .= ')';
                    break;
                case 'App\Models\Jadwal\TA':
                    $name = $detailJadwal->tipe . ' ' . $detailJadwal->nama_mahasiswa;
                    break;
                case 'App\Models\Jadwal\Lain':
                    $name = $detailJadwal->tipe;
                    break;
                default:
                    break;
            }
        }

        return $name;
    }
}
