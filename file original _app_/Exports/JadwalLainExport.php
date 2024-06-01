<?php

namespace App\Exports;

use App\Models\Jadwal\Lain;

class JadwalLainExport extends JadwalExport
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        return Lain::query()->with(['jadwal', 'pembuat'])
            ->whereHas('jadwal', function ($query) {
                $query->where('tanggal_mulai', '>=', $this->dariTanggal)
                    ->where('tanggal_mulai', '<=', $this->sampaiTanggal);
        });
    }

    /**
     * @var \App\Models\Jadwal\Lain $row
     */
    public function map($row): array
    {
        $jadwal = $row->jadwal;
        $pembuat = $row->pembuat;

        return [
            $row->tipe,
            $jadwal->tanggal_mulai->isoFormat('dddd, DD MMMM YYYY'),
            $jadwal->waktu_mulai->isoFormat('HH:mm'),
            $jadwal->waktu_selesai->isoFormat('HH:mm'),
            $row->dosen,
            $jadwal->ruangan,
            $row->deskripsi,
            $pembuat->name,
            $jadwal->created_at,
            $jadwal->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Jenis',
            'Hari/Tanggal',
            'Waktu Mulai',
            'Waktu Akhir',
            'Dosen',
            'Ruangan',
            'Keterangan',
            'Dibuat Oleh',
            'Dibuat Pada',
            'Terakhir Diubah',
        ];
    }
}
