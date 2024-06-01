<?php

namespace App\Http\Resources;

use App\Models\Jadwal\Jadwal;
use App\Traits\JadwalDateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalTaResource extends JsonResource
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
            'tanggal_mulai' => $this->parseDatetoLocal($this->tanggal_mulai),
            'tipe' => $this->detailJadwal->tipe,
            'nama_mahasiswa' => $this->detailJadwal->nama_mahasiswa,
            'nim' => $this->detailJadwal->nim,
            'dosen_pembimbing_1' => $this->detailJadwal->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $this->detailJadwal->dosen_pembimbing_2,
            'judul' => $this->detailJadwal->judul,
            'dosen_penguji_1' => $this->detailJadwal->dosen_penguji_1,
            'dosen_penguji_2' => $this->detailJadwal->dosen_penguji_2,
            'waktu_mulai' => $this->parseTimeToLocal($this->waktu_mulai),
            'waktu_selesai' => $this->parseTimeToLocal($this->waktu_selesai),
            'ruangan' => $this->detailJadwal->ruangan,
            'tautan' => $this->linkify($this->detailJadwal->tautan), // Apply linkify here
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
