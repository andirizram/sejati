<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class JadwalExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize,
    WithStyles
{
    use Exportable;

    protected $weekDaysOrder = [
        'Senin' => 1,
        'Selasa' => 2,
        'Rabu' => 3,
        'Kamis' => 4,
        'Jumat' => 5,
        'Sabtu' => 6,
        'Minggu' => 7,
    ];

    public function __construct(
        protected string $dariTanggal,
        protected string $sampaiTanggal,
    ) {}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function prepareRows($rows)
    {
        return $rows->transform(function ($row) {
            if ($row->jadwal) {
                $row->jadwal->waktu_mulai = Carbon::parse($row->jadwal->waktu_mulai)
                    ->setTimezone(env('CLIENT_TIMEZONE', config('app.timezone')));
                $row->jadwal->waktu_selesai = Carbon::parse($row->jadwal->waktu_selesai)
                    ->setTimezone(env('CLIENT_TIMEZONE', config('app.timezone')));
            }

            $row->created_at = Carbon::parse($row->created_at)
                ->setTimezone('Asia/Jakarta');
            $row->updated_at = Carbon::parse($row->updated_at)
                ->setTimezone('Asia/Jakarta');

            return $row;
        });
    }

    public function sortRows($rows)
    {
        return $rows->sortBy(function ($row) {
            $day = Carbon::parse($row->jadwal->tanggal_mulai)->isoFormat('dddd');
            $date = Carbon::parse($row->jadwal->tanggal_mulai)->format('Y-m-d');
            return [$this->weekDaysOrder[$day], $date];
        });
    }
}
