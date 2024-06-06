<?php

namespace App\Http\Resources;

use App\Traits\JadwalDateTime;
use App\Traits\JadwalName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class KalenderResource extends JsonResource
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
            'deskripsi' => $this->detailJadwal->deskripsi,
            'is_tabrakan' => $this->isTabrakan(),
        ];
    }

    public function isTabrakan(): bool
    {
        $user = Auth::user();
        $jadwalDiambil = $user::getJadwalDiambil();


        foreach ($jadwalDiambil as $jadwal) {
            if ($this->id == $jadwal->id) {
                continue;
            }

            $jadwal_mulai = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal->waktu_mulai));
            $jadwal_selesai = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal->waktu_selesai));

            $current_mulai = Carbon::createFromTimeString($this->parseTimeToLocal($this->waktu_mulai));
            $current_selesai = Carbon::createFromTimeString($this->parseTimeToLocal($this->waktu_selesai));


            if ($this->tanggal_mulai->isSameDay($jadwal->tanggal_mulai)) {
                if (($current_mulai->greaterThan($jadwal_mulai) && $current_mulai->lessThan($jadwal_selesai))
                    || ($current_selesai->greaterThan($jadwal_mulai) && $current_selesai->lessThan($jadwal_selesai))
                    || ($jadwal_mulai->greaterThan($current_mulai) && $jadwal_mulai->lessThan($current_selesai))
                    || ($jadwal_selesai->greaterThan($current_mulai) && $jadwal_selesai->lessThan($current_selesai))
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
