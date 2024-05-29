<?php

namespace App\Http\Resources;

use App\Traits\JadwalDateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalProdiResource extends JsonResource
{
    use JadwalDateTime;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tanggal_mulai' => $this->parseDateToDay($this->tanggal_mulai),
            'hari' => $this->parseDateToDay($this->tanggal_mulai),
            'waktu_mulai' => $this->parseTimeToLocal($this->waktu_mulai),
            'waktu_selesai' => $this->parseTimeToLocal($this->waktu_selesai),
            'mata_kuliah' => $this->detailJadwal->mata_kuliah,
            'sks' => $this->detailJadwal->sks,
            'kelas' => $this->detailJadwal->kelas,
            'semester' => $this->detailJadwal->semester,
            'dosen' => $this->detailJadwal->dosen,
            'ruangan' => $this->ruangan,
            'deskripsi' => $this->linkify($this->detailJadwal->deskripsi) // Apply linkify here
        ];
    }

    protected function linkify($text)
    {
        return preg_replace(
            "/\b(http:\/\/|https:\/\/|www\.)[^ \f\n\r\t\v\"<>]*[^ \f\n\r\t\v\"<>\.,!?\[\]{}()*;:'\"!&$]+/iu",
            "<a href=\"$0\" target=\"_blank\">$0</a>",
            $text
        );
    }
}
