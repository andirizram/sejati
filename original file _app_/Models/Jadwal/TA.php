<?php

namespace App\Models\Jadwal;

use App\Traits\HasPembuat;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TA extends Model implements DetailJadwal
{
    use HasFactory, HasPembuat;

    protected $table = 'tas';

    protected $fillable = [
        'tipe',
        'nama_mahasiswa',
        'nim',
        'dosen_pembimbing_1',
        'dosen_pembimbing_2',
        'judul',
        'dosen_penguji_1',
        'dosen_penguji_2',
        'tautan',
        'deskripsi',
        'user_id_pembuat'
    ];

    const TIPE = [
        'Sidang Akhir',
        'Seminar Proposal'
    ];

    public function jadwal(): MorphOne
    {
        return $this->morphOne(Jadwal::class, 'detailJadwal', 'kelas_detail_jadwal', 'id_detail_jadwal');
    }

    public function getDosenArrayAttribute(): array
    {
        return [
            $this->dosen_pembimbing_1,
            $this->dosen_pembimbing_2,
            $this->dosen_penguji_1,
            $this->dosen_penguji_2
        ];
    }
}
