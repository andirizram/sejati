<?php

namespace App\Exports;

use App\Models\Jadwal\Prodi;
use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;

class JadwalProdiExport extends JadwalExport
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
        return Prodi::query()
            ->select('prodis.*')
            ->join('jadwals', function($join) {
                $join->on('prodis.id', '=', 'jadwals.id_detail_jadwal')
                    ->where('jadwals.kelas_detail_jadwal', '=', Prodi::class);
            })
            ->whereBetween('jadwals.tanggal_mulai', [$this->dariTanggal, $this->sampaiTanggal])
            ->orderByRaw($caseQuery)
            ->orderBy('jadwals.tanggal_mulai', 'asc');
    }

    /**
     * @param \App\Models\Jadwal\Prodi $row
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
