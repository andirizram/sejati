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

    /**
     * @param \Illuminate\Database\Eloquent\Collection $rows
     */
    public function prepareRows($rows)
    {
        return $rows->transform(function ($row) {
            $row->jadwal->waktu_mulai = Carbon::parse($row->jadwal->waktu_mulai)
                ->setTimezone(env('CLIENT_TIMEZONE', config('app.timezone')));
            $row->jadwal->waktu_selesai = Carbon::parse($row->jadwal->waktu_selesai)
                ->setTimezone(env('CLIENT_TIMEZONE', config('app.timezone')));

            return $row;
        });
    }
}
