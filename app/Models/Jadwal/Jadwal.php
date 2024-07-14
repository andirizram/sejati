<?php

namespace App\Models\Jadwal;

use App\Models\Pengaturan;
use App\Models\PerubahanJadwal;
use App\Models\User;
use App\Traits\HasPembuat;
use App\Traits\JadwalDateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Jadwal extends Model
{
    use HasFactory, HasPembuat, JadwalDateTime;

    public const TANPA_PENGULANGAN = 0;
    public const PENGULANGAN_MINGGUAN = 1;
    public const PENGULANGAN_BULANAN = 2;
    public const PENGULANGAN_TAHUNAN = 3;

    protected $table = 'jadwals';

    const DAY = [
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu'
    ];

    const CATEGORIES = [
        'TA', 'TPB', 'Prodi', 'Lain'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
    ];

    protected $fillable = [
        'tanggal_mulai',
        'waktu_mulai',
        'waktu_selesai',
        'ruangan',
        'pengulangan',
        'id_detail_jadwal',
        'kelas_detail_jadwal',
        'user_id_pembuat'
    ];

    public static function mapPengulangan(string $teks): int
    {
        if (is_null($teks)) {
            $teks = '';
        }

        $teks = strtolower((string)$teks);

        switch ($teks) {
            case '':
            case '-':
            case '0':
            default:
                return static::TANPA_PENGULANGAN;
            case 'minggu':
            case 'mingguan':
                return static::PENGULANGAN_MINGGUAN;
            case 'bulan':
            case 'bulanan':
                return static::PENGULANGAN_BULANAN;
            case 'tahun':
            case 'tahunan':
                return static::PENGULANGAN_TAHUNAN;
        }
    }

    public static function buatDariFile(UploadedFile $file, $kelasDetailJadwal, User $userPembuat): Collection
    {
        $kelas = __NAMESPACE__ . '\\' . $kelasDetailJadwal;

        if (!class_exists($kelas)) {
            throw new InvalidArgumentException('Tipe jadwal tidak dikenali.');
        }

        $import = 'App\\Imports\\Jadwal' . $kelasDetailJadwal . 'Import';

        if (!class_exists($import)) {
            throw new Exception('Tipe jadwal tidak dapat dibuat melalui file.');
        }

        $detailJadwals = collect((new $import)->buatArray($file));

        DB::transaction(function () use ($detailJadwals, $kelas, $userPembuat): void {
            $detailJadwals->transform(function (array $attributes) use ($kelas, $userPembuat): DetailJadwal {
                $detailJadwal = new $kelas($attributes);
                $detailJadwal->pembuat()->associate($userPembuat);
                $detailJadwal->save();

                $jadwal = $detailJadwal->jadwal()->make($attributes);
                $jadwal->pembuat()->associate($userPembuat);
                $jadwal->save();

                return $detailJadwal->refresh()->loadMissing('jadwal');
            });
        });

        return $detailJadwals->pipeInto(Collection::class);
    }

    public static function parseDateTime($value): Carbon
    {
        return Carbon::parseFromLocale(
            $value,
            config('app.locale'),
            env('CLIENT_TIMEZONE', config('app.timezone'))
        )->setTimezone('UTC');
    }

    public function detailJadwal(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'kelas_detail_jadwal', 'id_detail_jadwal');
    }

    public static function getTabrakan()
    {
        $cacheKey = 'schedule_collisions';

        return Cache::remember($cacheKey, now()->addMinutes(30), function () {
            $akhir_kuliah = Carbon::parse(Pengaturan::where('key', 'tanggal_kuliah_terakhir')->first()->value);

            if ($akhir_kuliah->isPast()) {
                return collect([]);
            }

            $jadwalInstance = new self();
            $allSchedules = Jadwal::with('detailJadwal')->get();

            $result = $jadwalInstance->checkCollisions($allSchedules);

            return Jadwal::whereIn('id', $result)->get();
        });
    }

    private function checkCollisions($allSchedules)
    {
        $result = [];
        $events = [];

        // Create events for sweep line algorithm
        foreach ($allSchedules as $jadwal) {
            $events[] = ['time' => $jadwal['waktu_mulai'], 'type' => 'start', 'jadwal' => $jadwal];
            $events[] = ['time' => $jadwal['waktu_selesai'], 'type' => 'end', 'jadwal' => $jadwal];
        }

        // Sort events by time, with 'end' events before 'start' events if times are equal
        usort($events, function ($a, $b) {
            if ($a['time'] == $b['time']) {
                return $a['type'] === 'end' ? -1 : 1;
            }
            return $a['time'] < $b['time'] ? -1 : 1;
        });

        $active = [];

        // Sweep line algorithm to find collisions
        foreach ($events as $event) {
            if ($event['type'] === 'start') {
                foreach ($active as $activeEvent) {
                    if ($this->isConflict($event['jadwal'], $activeEvent)) {
                        $result[] = $event['jadwal']['id'];
                        $result[] = $activeEvent['id'];
                    }
                }
                $active[] = $event['jadwal'];
            } else {
                $active = array_filter($active, function ($activeEvent) use ($event) {
                    return $activeEvent['id'] !== $event['jadwal']['id'];
                });
            }
        }

        return array_unique($result);
    }

    private function isConflict($jadwal1, $jadwal2)
    {
        // Extract the relevant details
        $start1 = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal1['waktu_mulai']))->subMinutes(5);
        $end1 = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal1['waktu_selesai']))->addMinutes(5);
        $start2 = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal2['waktu_mulai']))->subMinutes(5);
        $end2 = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal2['waktu_selesai']))->addMinutes(5);

        $dosenOverlap = !empty(array_intersect($jadwal1['dosen_array'] ?? [], $jadwal2['dosen_array'] ?? []));
        $ruanganSama = $jadwal1['ruangan'] == $jadwal2['ruangan'];
        $tanggalSama = Carbon::createFromDate($jadwal1['tanggal_mulai'])->isSameDay($jadwal2['tanggal_mulai']);

        if ($tanggalSama && ($dosenOverlap || $ruanganSama)) {
            return ($start1->lessThan($end2) && $end1->greaterThan($start2));
        }

        return false;
    }

    public static function kosongkanData($category = null)
    {
        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            if ($category) {
                switch ($category) {
                    case 'TA':
                        $taJadwalIds = Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\TA')->pluck('id');
                        PerubahanJadwal::whereIn('jadwal_id', $taJadwalIds)->delete();
                        Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\TA')->delete();
                        TA::query()->delete();
                        break;
                    case 'TPB':
                        $tpbJadwalIds = Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\TPB')->pluck('id');
                        PerubahanJadwal::whereIn('jadwal_id', $tpbJadwalIds)->delete();
                        Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\TPB')->delete();
                        TPB::query()->delete();
                        break;
                    case 'Prodi':
                        $prodiJadwalIds = Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\Prodi')->pluck('id');
                        PerubahanJadwal::whereIn('jadwal_id', $prodiJadwalIds)->delete();
                        Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\Prodi')->delete();
                        Prodi::query()->delete();
                        break;
                    case 'Lain':
                        $lainJadwalIds = Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\Lain')->pluck('id');
                        PerubahanJadwal::whereIn('jadwal_id', $lainJadwalIds)->delete();
                        Jadwal::where('kelas_detail_jadwal', 'App\Models\Jadwal\Lain')->delete();
                        Lain::query()->delete();
                        break;
                    case 'all':
                        $allJadwalIds = self::pluck('id');
                        PerubahanJadwal::whereIn('jadwal_id', $allJadwalIds)->delete();
                        TA::query()->delete();
                        TPB::query()->delete();
                        Prodi::query()->delete();
                        Lain::query()->delete();
                        self::query()->delete();
                        break;
                    default:
                        throw new Exception('Kategori tidak dikenal.');
                }
            } else {
                $allJadwalIds = self::pluck('id');
                PerubahanJadwal::whereIn('jadwal_id', $allJadwalIds)->delete();
                TA::query()->delete();
                TPB::query()->delete();
                Prodi::query()->delete();
                Lain::query()->delete();
                self::query()->delete();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Refresh the cache after deleting schedules
            self::refreshCollisionCache();

        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    public static function refreshCollisionCache()
    {
        Cache::forget('schedule_collisions');
        Cache::remember('schedule_collisions', now()->addMinutes(30), function () {
            $akhir_kuliah = Carbon::parse(Pengaturan::where('key', 'tanggal_kuliah_terakhir')->first()->value);

            if ($akhir_kuliah->isPast()) {
                return collect([]);
            }

            $jadwalInstance = new self();
            $allSchedules = Jadwal::with('detailJadwal')->get();

            $result = $jadwalInstance->checkCollisions($allSchedules);

            return Jadwal::whereIn('id', $result)->get();
        });
    }
}
