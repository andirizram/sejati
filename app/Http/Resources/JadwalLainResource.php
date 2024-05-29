<?php

namespace App\Http\Resources;

use App\Traits\JadwalDateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalLainResource extends JsonResource
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
            'tipe' => $this->detailJadwal ? $this->detailJadwal->tipe : null,
            'tanggal_mulai' => $this->parseDateToLocal($this->tanggal_mulai),
            'hari' => $this->parseDateToday($this->tanggal_mulai),
            'waktu_mulai' => $this->parseTimeToLocal($this->waktu_mulai),
            'waktu_selesai' => $this->parseTimeToLocal($this->waktu_selesai),
            'dosen' => $this->detailJadwal ? $this->detailJadwal->dosen : null,
            'ruangan' => $this->ruangan,
            'deskripsi' => $this->linkify($this->detailJadwal ? $this->detailJadwal->deskripsi : null) // Apply linkify here
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
