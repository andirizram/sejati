<?php

namespace App\Models;

use App\Models\Jadwal\Jadwal;
use App\Models\Jadwal\Lain;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];


    public function jadwalDibuat(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'user_id_pembuat')
            ->with('detailJadwal');
    }

    // public function jadwalLainDibuat(): HasMany
    // {
    //     return $this->hasMany(Lain::class, 'user_id_pembuat');
    // }

    public function jadwalDiambil(): BelongsToMany
    {
        return $this->belongsToMany(Jadwal::class, 'user_mahasiswa_has_jadwals', 'user_id_mahasiswa', 'jadwal_id')
            ->with('detailJadwal');
    }

    public function jadwalBertabrakan()
    {
        $queryBertabrakan = $this->jadwalDiambil()
            ->select(['tanggal_mulai', 'waktu_mulai'])
            ->groupBy(['tanggal_mulai', 'waktu_mulai'])
            ->havingRaw('COUNT(`id`) > 1');

        return $this->jadwalDiambil()
            ->joinSub($queryBertabrakan, 'jadwal_bertabrakan', function (JoinClause $join): void {
                $join->on('jadwals.tanggal_mulai', '=', 'jadwal_bertabrakan.tanggal_mulai')
                    ->on('jadwals.waktu_mulai', '=', 'jadwal_bertabrakan.waktu_mulai');
            });
    }

    public function getTabrakan()
    {
        return $this->jadwalDiambil()->select('jadwals.id')->distinct()
            ->join('jadwals as j', function ($join) {
                $join->whereRaw('DAYOFWEEK(j.tanggal_mulai) = DAYOFWEEK(jadwals.tanggal_mulai)')
                    ->whereRaw('j.id <> jadwals.id')
                    ->whereRaw('jadwals.ruangan = j.ruangan')
                    ->where(function ($query) {
                        $query->whereRaw('j.waktu_mulai BETWEEN jadwals.waktu_mulai AND jadwals.waktu_selesai')
                            ->orWhereRaw('j.waktu_selesai BETWEEN jadwals.waktu_mulai AND jadwals.waktu_selesai');
                    });
            })
            ->pluck('id');
    }

    public static function getJadwalDiambil($userId = null)
    {
        $array_baru = [];
        $user = $userId ? User::find($userId) : Auth::user();
        $jadwalDiambil = $user->jadwalDiambil;
        $akhir_kuliah = \Carbon\Carbon::parse(Pengaturan::where('key', 'tanggal_kuliah_terakhir')->first()->value);

        if ($akhir_kuliah->isPast()) {
            return collect($array_baru);
        }

        $jadwalDiambil->map(function ($jadwal) use (&$array_baru, $akhir_kuliah) {

            if ($jadwal->pengulangan) {
                array_push($array_baru, $jadwal);

                $selisih = Carbon::parse($jadwal->tanggal_mulai)->diffInWeeks($akhir_kuliah);

                foreach (range(1, $selisih) as $i) {
                    $jadwal_baru = $jadwal->replicate();
                    $jadwal_baru->id = $jadwal->id;
                    $jadwal_baru->tanggal_mulai = Carbon::parse($jadwal->tanggal_mulai)->addWeeks($i);
                    array_push($array_baru, $jadwal_baru);
                }
            } else {
                array_push($array_baru, $jadwal);
            }
        });
        return collect($array_baru);
    }
}
