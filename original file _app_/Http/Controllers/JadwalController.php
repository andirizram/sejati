<?php

namespace App\Http\Controllers;

use App\Exports\JadwalExport;
use App\Http\Resources\JadwalResource;
use App\Http\Resources\KalenderResource;
use App\Models\Jadwal\Jadwal;
use App\Models\Jadwal\Lain;
use App\Models\Jadwal\Prodi;
use App\Models\Jadwal\TA;
use App\Models\Jadwal\TPB;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use ReflectionClass;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'menu']);
    }

    public function buat(): Response
    {
        return response()->view('upload_jadwal');
    }

    public function simpan(): RedirectResponse
    {
        return redirect()->route('');
    }

    public function pribadi(Request $request): \Illuminate\View\View
    {
        $jadwalDiambil = User::getJadwalDiambil();
        $jadwals = KalenderResource::collection($jadwalDiambil)->toArray($request);
        return view('kalender', compact('jadwals'));
    }

    public function tabrakan(Request $request)
    {
        $jadwals = JadwalResource::collection(Jadwal::getTabrakan())->toArray($request);
        return view('jadwal_tabrakan', compact('jadwals'));
    }

    public function ambil(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $user = $request->user();

        if ($user->jadwalDiambil()->find($jadwal->id)) {
            $user->jadwalDiambil()->detach($jadwal);
            $message = 'Jadwal berhasil dilepas.';
            $status = 'success';
        } else {
            try {
                $user->jadwalDiambil()->attach($jadwal);
                $message = 'Jadwal berhasil diambil.';
                $status = 'success';
            } catch (\Exception $e) {
                $message = 'Gagal mengambil jadwal.';
                $status = 'error';
            }
        }

        return redirect()->back()->with($status, $message);
    }

    public function lepas(): RedirectResponse
    {
        return redirect()->back();
    }

    public function index()
    {
        return view('jadwal.index');
    }

    public function exportView(): \Illuminate\View\View
    {
        $jenisJadwal = [
            'Jadwal ' . (new ReflectionClass(new Prodi))->getShortName(),
            'Jadwal ' . (new ReflectionClass(new TPB))->getShortName(),
            'Jadwal ' . (new ReflectionClass(new TA))->getShortName(),
            'Jadwal ' . (new ReflectionClass(new Lain))->getShortName(),
        ];
        $minDate = Jadwal::oldest('tanggal_mulai')->first()?->tanggal_mulai?->format('Y-m-d') ?: today()->format('Y-m-d');
        $maxDate = Jadwal::latest('tanggal_mulai')->first()?->tanggal_mulai?->format('Y-m-d') ?: today()->format('Y-m-d');
        return view('jadwal_export', [
            'jenisJadwal' => $jenisJadwal,
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

    public function export(Request $request)
    {
        $jenisJadwal = [
            'Jadwal ' . (new ReflectionClass(new Prodi))->getShortName(),
            'Jadwal ' . (new ReflectionClass(new TPB))->getShortName(),
            'Jadwal ' . (new ReflectionClass(new TA))->getShortName(),
            'Jadwal ' . (new ReflectionClass(new Lain))->getShortName(),
        ];

        $validated = $request->validate([
            'jenis_jadwal' => 'required|string|in:' . implode(',', array_keys($jenisJadwal)),
            'dari_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date',
        ]);

        $jenisJadwalTerpilih = $jenisJadwal[$validated['jenis_jadwal']];
        $jenisJadwalTerpilih = str_replace('Jadwal ', '', $jenisJadwalTerpilih);

        $jenisJadwalTerpilih = 'App\\Models\\Jadwal\\' . $jenisJadwalTerpilih;

        $kelasExport = "App\\Exports\\Jadwal"
            . (new ReflectionClass($jenisJadwalTerpilih))->getShortName()
            . 'Export';

        return (new $kelasExport(
            $validated['dari_tanggal'],
            $validated['sampai_tanggal'],
        ))
        ->download(
            $jenisJadwal[$validated['jenis_jadwal']] 
            . ' - ' 
            . $validated['dari_tanggal'] 
            . '-' 
            . $validated['sampai_tanggal']
            . '.xlsx',
        );
    }
}
