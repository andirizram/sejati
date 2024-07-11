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
        return Lain::query()
            ->select('lains.*')
            ->join('jadwals', function($join) {
                $join->on('lains.id', '=', 'jadwals.id_detail_jadwal')
                    ->where('jadwals.kelas_detail_jadwal', '=', Lain::class);
            })
            ->whereBetween('jadwals.tanggal_mulai', [$this->dariTanggal, $this->sampaiTanggal])
            ->orderByRaw($caseQuery)
            ->orderBy('jadwals.tanggal_mulai', 'asc');
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
