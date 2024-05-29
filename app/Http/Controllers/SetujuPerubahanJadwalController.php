<?php

namespace App\Http\Controllers;

use App\Models\Jadwal\Jadwal;
use App\Models\PerubahanJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetujuPerubahanJadwalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $perubahan = PerubahanJadwal::findOrFail($id);

        $perubahan->update([
            'status' => PerubahanJadwal::STATUS_SETUJU,
            'user_status_penindak' => Auth::user()->id
        ]);

        $jadwal_id = $perubahan->jadwal_id;

        $jadwal = Jadwal::findOrFail($jadwal_id);

        $jadwal->update([
            'pengulangan' => $perubahan->pengulangan,
            'tanggal_mulai' => $perubahan->tanggal_mulai,
            'waktu_mulai' => $perubahan->waktu_mulai,
            'waktu_selesai' => $perubahan->waktu_selesai,
            'ruangan' => $perubahan->ruangan
        ]);

        return redirect()->back()->withSuccess('Berhasil menyetujui perubahan jadwal');

    }
}
