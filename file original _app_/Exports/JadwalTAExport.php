<?php

namespace App\Exports;

use App\Models\Jadwal\TA;

class JadwalTAExport extends JadwalExport
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        return TA::query()->with(['jadwal', 'pembuat'])
            ->whereHas('jadwal', function ($query) {
                $query->where('tanggal_mulai', '>=', $this->dariTanggal)
                    ->where('tanggal_mulai', '<=', $this->sampaiTanggal);
            });
    }

    /**
     * @param \App\Models\Jadwal\TA $row
     */
    public function map($row): array
    {
        $jadwal = $row->jadwal;
        $pembuat = $row->pembuat;

        return [
            $jadwal->tanggal_mulai->isoFormat('dddd, DD MMMM YYYY'),
            $row->tipe,
            $row->nama_mahasiswa,
            $row->nim,
            $row->dosen_pembimbing_1,
            $row->dosen_pembimbing_2,
            $row->judul,
            $row->dosen_penguji_1,
            $row->dosen_penguji_2,
            $jadwal->waktu_mulai->isoFormat('HH:mm'),
            $jadwal->waktu_selesai->isoFormat('HH:mm'),
            $jadwal->ruangan,
            $row->tautan,
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
            'Jenis',
            'Nama Mahasiswa',
            'NIM',
            'Pembimbing 1',
            'Pembimbing 2',
            'Judul TA',
            'Penguji 1',
            'Penguji 2',
            'Waktu Mulai',
            'Waktu Berakhir',
            'Ruangan',
            'Tautan',
            'Deskripsi',
            'Dibuat Oleh',
            'Dibuat Pada',
            'Terakhir Diubah',
        ];
    }
}
