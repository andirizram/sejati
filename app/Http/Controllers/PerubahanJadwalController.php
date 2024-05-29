<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerubahanJadwalRequest;
use App\Http\Resources\JadwalResource;
use App\Http\Resources\PerubahanJadwalResource;
use Carbon\Carbon;
use App\Models\Jadwal\Jadwal;
use App\Models\PerubahanJadwal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PerubahanJadwalController extends Controller
{

    public function index()
    {
        $pengjuanData = PerubahanJadwal::all();

        $pengajuans = PerubahanJadwalResource::collection($pengjuanData)->toArray(app('request'));

        return response()->view('daftar_pengajuan', compact('pengajuans'));
    }

    public function create()
    {
        $jadwalData = Jadwal::with('detailJadwal')->get();
        $jadwals = JadwalResource::collection($jadwalData)->toArray(app('request'));


        $pengajuanData = PerubahanJadwal::with('jadwal')
            ->where('user_id_pembuat', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $pengajuans = PerubahanJadwalResource::collection($pengajuanData)->toArray(app('request'));


        return view('pengajuan', compact('jadwals', 'pengajuans'));
    }

    public function store(StorePerubahanJadwalRequest $request)
    {
        PerubahanJadwal::create([
            'jadwal_id' => $request->jadwal_id,
            'user_id_pembuat' => Auth::user()->id,
            'tanggal_mulai' => isset($request->pengulangan) ? Carbon::parseFromLocale($request->hari) : Carbon::parseFromLocale($request->tanggal),
            'alasan' => $request->alasan,
            'waktu_mulai' => Jadwal::parseDateTime($request->waktu_mulai),
            'waktu_selesai' => Jadwal::parseDateTime($request->waktu_selesai),
            'pengulangan' => isset($request->pengulangan) ? 1 : 0,
            'ruangan' => $request->ruangan
        ]);

        return redirect()->back()->withSuccess('Data berhasil diajukan');
    }

    public function daftar(): Response
    {
        return response()->view('daftar_pengajuan');
    }

    public function buat(): Response
    {
        return response()->view('pengajuan');
    }

    public function simpan(): RedirectResponse
    {
        return redirect()->back();
    }

    public function tindak(): RedirectResponse
    {
        return redirect()->back();
    }
}
