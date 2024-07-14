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

class JadwalTAImport implements ToModel, WithHeadingRow
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
            'tipe' => $row['type'],
            'nama_mahasiswa' => $row['name'],
            'dosen_pembimbing_1' => $row['pembimbing1'],
            'dosen_pembimbing_2' => $row['pembimbing2'],
            'judul' => $row['title'],
            'dosen_penguji_1' => $row['penguji1'],
            'dosen_penguji_2' => $row['penguji2'],
            'tautan' => $row['link'],
            'deskripsi' => key_exists('deskripsi', $row) ? $row['deskripsi'] : null,
            'tanggal_mulai' => $row['date'],
            'waktu_mulai' => $row['start_time'],
            'waktu_selesai' => $row['end_time'],
            'ruangan' => $row['room'],
            'pengulangan' => Jadwal::mapPengulangan($row['pengulangan'] ?? ''),
        ];
    }

    public function buatArray($filePath = null, ?string $disk = null, ?string $readerType = null): array
    {
        $array = Arr::flatten($this->toArray($filePath, $disk, $readerType), 1);

        return Arr::map($array, fn(array $value) => [
            'tipe' => $value['tipe'],
            'nama_mahasiswa' => $value['nama_mahasiswa'],
            'dosen_pembimbing_1' => $value['dosen_pembimbing_1'],
            'dosen_pembimbing_2' => $value['dosen_pembimbing_2'],
            'judul' => $value['judul'],
            'dosen_penguji_1' => $value['dosen_penguji_1'],
            'dosen_penguji_2' => $value['dosen_penguji_2'],
            'tautan' => $value['tautan'],
            'deskripsi' => $value['deskripsi'],
            'nim' => $value['nim'],
            'tanggal_mulai' => Carbon::parseFromLocale($value['tanggal_mulai']),
            'waktu_mulai' => $this->parseExcelTime($value['waktu_mulai']),
            'waktu_selesai' => $this->parseExcelTime($value['waktu_selesai']),
            'ruangan' => $value['ruangan'],
            'pengulangan' => Jadwal::mapPengulangan($value['pengulangan'] ?? ''),
        ]);
    }

    protected function parseDateTime($value): Carbon
    {
        return Jadwal::parseDateTime($value);
    }
}
