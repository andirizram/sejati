<?php

namespace App\Http\Controllers;

use App\Http\Resources\JadwalResource;
use App\Models\Jadwal\Jadwal;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\View\View
    {
        $jadwalDiambil = Jadwal::getJadwalDiambil();
        $jadwals = JadwalResource::collection($jadwalDiambil)->toArray($request);
        return view('kalender', compact('jadwals'));
    }
}
