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
        // Define the week days order
        $weekDaysOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        // Create a raw query to get the day order
        $caseQuery = "CASE ";
        foreach ($weekDaysOrder as $day => $order) {
            $caseQuery .= "WHEN DAYNAME(jadwals.tanggal_mulai) = '$day' THEN $order ";
        }
        $caseQuery .= "END";

        // Join with the jadwals table and sort based on tanggal_mulai
        return TA::query()
            ->select('tas.*')
            ->join('jadwals', function($join) {
                $join->on('tas.id', '=', 'jadwals.id_detail_jadwal')
                    ->where('jadwals.kelas_detail_jadwal', '=', TA::class);
            })
            ->whereBetween('jadwals.tanggal_mulai', [$this->dariTanggal, $this->sampaiTanggal])
            ->orderByRaw($caseQuery)
            ->orderBy('jadwals.tanggal_mulai', 'asc');
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
