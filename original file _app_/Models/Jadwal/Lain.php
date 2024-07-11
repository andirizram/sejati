<?php

namespace App\Models\Jadwal;

use App\Traits\HasPembuat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Lain extends Model implements DetailJadwal
{
    use HasFactory, HasPembuat;

    protected $table = 'lains';

    protected $fillable = [
        'tipe',
        'dosen',
        'deskripsi',
        'user_id_pembuat'
    ];

    public function jadwal(): MorphOne
    {
        return $this->morphOne(Jadwal::class, 'detailJadwal', 'kelas_detail_jadwal', 'id_detail_jadwal');
    }

    public function getDosenArrayAttribute(): array
    {
        return explode(', ', $this->dosen);
    }
}
