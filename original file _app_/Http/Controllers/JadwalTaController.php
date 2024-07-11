<?php

namespace App\Http\Controllers;


use App\Models\Jadwal\TA;
use Illuminate\Support\Facades\Auth;

class JadwalTaController extends JadwalBaseController
{
    protected string $model = TA::class;

    protected string $module = 'TA';

    public function __construct()
    {
        $this->makeColumn();
    }

    private function makeColumn()
    {
        $this->columns = [
            'tanggal_mulai' => 'Hari/Tanggal',
            'tipe' => 'Jenis',
            'nama_mahasiswa' => 'Nama',
            'nim' => 'NIM',
            'dosen_pembimbing_1' => 'Pembimbing 1',
            'dosen_pembimbing_2' => 'Pembimbing 2',
            'judul' => 'Judul TA',
            'dosen_penguji_1' => 'Penguji 1',
            'dosen_penguji_2' => 'Penguji 2',
            'waktu_mulai' => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Berakhir',
            'tautan' => 'Tautan',
            'deskripsi' => 'Deskripsi',
        ];
    }

    public function save($request, $id = null)
    {
        if ($id) {
            return TA::findOrFail($id)->update([
                'tipe' => $request->tipe,
                'nim' => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
                'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
                'judul' => $request->judul,
                'dosen_penguji_1' => $request->dosen_penguji_1,
                'dosen_penguji_2' => $request->dosen_penguji_2,
                'tautan' => $request->tautan,
                'deskripsi' => $request->deskripsi,
                'user_id_pembuat' => Auth::user()->id
            ]);
        }

        return TA::create([
            'tipe' => $request->tipe,
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'judul' => $request->judul,
            'dosen_penguji_1' => $request->dosen_penguji_1,
            'dosen_penguji_2' => $request->dosen_penguji_2,
            'tautan' => $request->tautan,
            'deskripsi' => $request->deskripsi,
            'user_id_pembuat' => Auth::user()->id
        ]);
    }
}
