<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\JadwalName;
use App\Traits\JadwalDateTime;

class PerubahanJadwalResource extends JsonResource
{
    use JadwalName, JadwalDateTime;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Ensure 'detailJadwal' is loaded and it is an instance of Lain
        if ($this->jadwal && $this->jadwal->detailJadwal instanceof \App\Models\Jadwal\Lain) {
            $tipe = $this->jadwal->detailJadwal->tipe;
        } else {
            $tipe = 'Unknown'; // Default or error handling case
        }

        // Log::info('Detail Jadwal Type:', ['tipe' => $tipe]);

        return [
            'id' => $this->id,
            'name' => $this->name($this->jadwal->kelas_detail_jadwal, $this->jadwal->detailJadwal),
            'tipe' => $tipe,
            'alasan' => $this->alasan,
            'status' => $this->status,
            'tanggal_mulai' => $this->tanggal_mulai,
            'waktu_mulai' => $this->parseTimeToLocal($this->waktu_mulai),
            'waktu_selesai' => $this->parseTimeToLocal($this->waktu_selesai),
            'pembuat' => $this->pembuat->name,
            'ruangan' => $this->ruangan
        ];
    }
}
