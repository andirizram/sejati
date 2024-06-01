<?php

namespace App\Models\Jadwal;

use App\Models\Pengaturan;
use App\Models\PerubahanJadwal;
use App\Models\User;
use App\Traits\HasPembuat;
use App\Traits\JadwalDateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
        $array_baru = [];
        $akhir_kuliah = \Carbon\Carbon::parse(Pengaturan::where('key', 'tanggal_kuliah_terakhir')->first()->value);

        if ($akhir_kuliah->isPast()) {
            return collect($array_baru);
        }

        Jadwal::get()->map(function ($jadwal) use (&$array_baru, $akhir_kuliah) {

            if ($jadwal->pengulangan) {
                $jadwal_baru = $jadwal->replicate();
                $jadwal_baru->id = $jadwal->id;
                $jadwal_baru->dosen_array = $jadwal->detailJadwal->dosen_array ?? [];
                array_push($array_baru, $jadwal_baru->toArray());

                $selisih = Carbon::parse($jadwal->tanggal_mulai)->diffInWeeks($akhir_kuliah);

                foreach (range(1, $selisih) as $i) {
                    $jadwal_baru = $jadwal->replicate();
                    $jadwal_baru->id = $jadwal->id;
                    $jadwal_baru->dosen_array = $jadwal->detailJadwal->dosen_array ?? [];
                    $jadwal_baru->tanggal_mulai = Carbon::parse($jadwal->tanggal_mulai)->addWeeks($i);
                    array_push($array_baru, $jadwal_baru->toArray());
                }
            } else {
                $jadwal_baru = $jadwal->replicate();
                $jadwal_baru->id = $jadwal->id;
                $jadwal_baru->dosen_array = $jadwal->detailJadwal->dosen_array ?? [];
                array_push($array_baru, $jadwal_baru->toArray());
            }
        });

        $model = new self();
        $result = [];
        foreach ($array_baru as $jadwal) {

            if ($model->isTabrakan($jadwal)) {
                array_push($result, $jadwal['id']);
            }
        }

        return Jadwal::whereIn('id', $result)->get();
    }

    public function isTabrakan($data)
    {
        $jadwalDiambil = Jadwal::get();

        foreach ($jadwalDiambil as $jadwal) {
            if ($data['id'] == $jadwal->id) {
                continue;
            }

            $jadwal_mulai = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal->waktu_mulai));
            $jadwal_selesai = Carbon::createFromTimeString($this->parseTimeToLocal($jadwal->waktu_selesai));

            $current_tanggal_mulai = Carbon::createFromDate($data['tanggal_mulai']);
            $current_mulai = Carbon::createFromTimeString($this->parseTimeToLocal($data['waktu_mulai']));
            $current_selesai = Carbon::createFromTimeString($this->parseTimeToLocal($data['waktu_selesai']));

            $dosen_overlap = count(array_intersect($jadwal->detailJadwal->dosen_array, $data['dosen_array'])) > 0;
            $ruangan_sama = $jadwal->ruangan == $data['ruangan'];

            if (
                $current_tanggal_mulai->isSameDay($jadwal->tanggal_mulai) &&
                (
                    $dosen_overlap ||
                    $ruangan_sama
                ) &&
                (
                    $current_mulai->between($jadwal_mulai, $jadwal_selesai) ||
                    $current_selesai->between($jadwal_mulai, $jadwal_selesai) ||
                    $jadwal_mulai->between($current_mulai, $current_selesai) ||
                    $jadwal_selesai->between($current_mulai, $current_selesai)
                )
            ) {
                return true;
            }
        }

        return false;
    }

    public static function kosongkanData()
    {
        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            TA::query()->delete();
            TPB::query()->delete();
            Prodi::query()->delete();
            Lain::query()->delete();
            self::query()->delete();
            PerubahanJadwal::query()->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }
}
