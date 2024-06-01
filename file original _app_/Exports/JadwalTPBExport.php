<?php

namespace App\Exports;

use App\Models\Jadwal\TPB;

class JadwalTPBExport extends JadwalExport
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        return TPB::query()->with(['jadwal', 'pembuat'])
            ->whereHas('jadwal', function ($query) {
                $query->where('tanggal_mulai', '>=', $this->dariTanggal)
                    ->where('tanggal_mulai', '<=', $this->sampaiTanggal);
        });
    }

    /**
     * @var \App\Models\Jadwal\TPB $row
     */
    public function map($row): array
    {
        $jadwal = $row->jadwal;
        $pembuat = $row->pembuat;

        return [
            $jadwal->pengulangan
                ? $jadwal->tanggal_mulai->isoFormat('dddd')
                : $jadwal->tanggal_mulai->isoFormat('dddd, DD MMMM YYYY'),
            $jadwal->waktu_mulai->isoFormat('HH:mm'),
            $jadwal->waktu_selesai->isoFormat('HH:mm'),
            $row->mata_kuliah,
            $row->sks,
            $row->kelas,
            $row->semester,
            $row->dosen,
            $jadwal->ruangan,
            $row->deskripsi,
            $pembuat->name,
            $row->created_at->isoFormat('dddd, DD MMMM YYYY HH:mm'),
            $row->updated_at->isoFormat('dddd, DD MMMM YYYY HH:mm'),
        ];
    }

    public function headings(): array
    {
        return [
            'Hari/Tanggal',
            'Waktu Mulai',
            'Waktu Berakhir',
            'Mata Kuliah',
            'SKS',
            'Kelas',
            'Semester',
            'Dosen',
            'Ruangan',
            'Keterangan',
            'Dibuat Oleh',
            'Dibuat Pada',
            'Terakhir Diubah',
        ];
    }
}
