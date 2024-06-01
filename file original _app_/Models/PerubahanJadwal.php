<?php

namespace App\Models;

use App\Models\Jadwal\Jadwal;
use App\Traits\HasPembuat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerubahanJadwal extends Model
{
    use HasPembuat;

    public const STATUS_TUNGGU = 0;
    public const STATUS_TOLAK = -1;
    public const STATUS_SETUJU = 1;

    public const STATUS = [
        self::STATUS_TUNGGU => 'Menunggu Approval',
        self::STATUS_TOLAK => 'Ditolak',
        self::STATUS_SETUJU => 'Disetujui'
    ];

    protected $table = 'perubahan_jadwals';

    protected $fillable = [
        'jadwal_id', 'user_id_pembuat', 'status', 'user_id_penindak', 'tanggal_mulai', 'waktu_mulai', 'waktu_selesai', 'ruangan', 'alasan', 'pengulangan'
    ];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function penindak(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_penindak');
    }
}
