<?php

namespace App\Http\Resources;

use App\Traits\JadwalDateTime;
use App\Traits\JadwalName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class JadwalResource extends JsonResource
{
    use JadwalName, JadwalDateTime;

    protected string $model;

    public function __construct($resource, $model)
    {
        parent::__construct($resource);
        $this->model = $model;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pengulangan' => $this->pengulangan,
            'tanggal_mulai' => $this->tanggal_mulai->isoFormat('dddd, D MMMM Y'),
            'tanggal' => $this->tanggal_mulai->format('Y-m-d'),
            'hari' => $this->parseDateToDay($this->tanggal_mulai),
            'waktu_mulai' => $this->parseTimeToLocal($this->waktu_mulai),
            'waktu_selesai' => $this->parseTimeToLocal($this->waktu_selesai),
            'ruangan' => $this->ruangan,
            'detail_jadwal' => $this->detailJadwal,
            'tipe' => explode("\\", $this->kelas_detail_jadwal)[3],
            'name' => $this->name($this->kelas_detail_jadwal, $this->detailJadwal),
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
