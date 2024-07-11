<?php

namespace App\Imports;

use App\Models\Jadwal\Jadwal;
use App\Traits\JadwalDateTime;
use ErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class JadwalLainImport implements ToModel, WithHeadingRow
{
    use Importable, JadwalDateTime;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return [
            'tipe' => $row['tipe'],
            'dosen' => $row['dosen'],
            'deskripsi' => $row['deskripsi'],
            'tanggal_mulai' => $row['tanggal_mulai'],
            'waktu_mulai' => $row['waktu_mulai'],
            'waktu_selesai' => $row['waktu_selesai'],
            'ruangan' => $row['ruangan'],
            'pengulangan' => Jadwal::mapPengulangan($row['pengulangan']),
        ];
    }

    public function buatArray($filePath = null, ?string $disk = null, ?string $readerType = null): array
    {
        $array = Arr::flatten($this->toArray($filePath, $disk, $readerType), 1);

        return Arr::map($array, fn(array $value) => [
            'tipe' => $value['tipe'],
            'dosen' => $value['dosen'],
            'deskripsi' => $value['deskripsi'],
            'tanggal_mulai' => Carbon::parseFromLocale($value['tanggal_mulai']),
            'waktu_mulai' => $this->parseExcelTime($value['waktu_mulai']),
            'waktu_selesai' => $this->parseExcelTime($value['waktu_selesai']),
            'ruangan' => $value['ruangan'],
            'pengulangan' => Jadwal::mapPengulangan($value['pengulangan']),
        ]);
    }

    protected function parseDateTime($value): Carbon
    {
        return Jadwal::parseDateTime($value);
    }
}
