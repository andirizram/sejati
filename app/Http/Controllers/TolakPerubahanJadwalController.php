<?php

namespace App\Http\Controllers;

use App\Models\PerubahanJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TolakPerubahanJadwalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $perubahan = PerubahanJadwal::findOrFail($id);

        $perubahan->update([
            'status' => PerubahanJadwal::STATUS_TOLAK,
            'user_id_penindak' => Auth::user()->id
        ]);

        return redirect()->back()->withSuccess('Berhasil menolak perubahan jadwal');
    }
}
