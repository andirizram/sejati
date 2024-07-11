<?php

namespace App\Http\Controllers;


use App\Models\Jadwal\Lain;
use Illuminate\Support\Facades\Auth;

class JadwalLainController extends JadwalBaseController
{
    protected string $model = Lain::class;

    protected string $module = 'Lain';

    protected array $columns = [];

    public function __construct()
    {
        $this->makeColumn();
    }

    private function makeColumn()
    {
        $this->columns = [
            'tipe' => 'Jenis',
            'tanggal_mulai' => 'Hari/Tanggal',
            'waktu_mulai' => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Akhir',
            'dosen' => 'Dosen',
            'ruangan' => 'Ruangan',
            'deskripsi' => 'Keterangan'
        ];
    }

    public function save($request, $id = null)
    {
        if ($id) {
            return Lain::findOrFail($id)->update([
                'tipe' => $request->tipe,
                'dosen' => $request->dosen,
                'deskripsi' => $request->deskripsi,
                'user_id_pembuat' => Auth::user()->id
            ]);
        }

        return Lain::create([
            'tipe' => $request->tipe,
            'dosen' => $request->dosen,
            'deskripsi' => $request->deskripsi,
            'user_id_pembuat' => Auth::user()->id
        ]);
    }
}
