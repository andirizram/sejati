<?php

namespace App\Http\Controllers;

use App\Models\Jadwal\Prodi;
use Illuminate\Support\Facades\Auth;

class JadwalProdiController extends JadwalBaseController
{
    protected string $model = Prodi::class;

    protected string $module = 'Prodi';

    protected array $columns = [];

    public function __construct()
    {
        $this->makeColumn();
    }

    private function makeColumn()
    {
        $this->columns = [
            'tanggal_mulai' => 'Hari/Tanggal',
            'waktu_mulai' => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Berakhir',
            'mata_kuliah' => 'Mata Kuliah',
            'sks' => 'SKS',
            'kelas' => 'Kelas',
            'semester' => 'Semester',
            'dosen' => 'Dosen',
            'ruangan' => 'Ruangan',
            'deskripsi' => 'Keterangan',
        ];
    }

    public function save($request, $id = null)
    {
        if ($id) {
            return Prodi::findOrFail($id)->update([
                'tipe' => $request->tipe,
                'mata_kuliah' => $request->mata_kuliah,
                'sks' => $request->sks,
                'kelas' => $request->kelas,
                'semester' => $request->semester,
                'dosen' => $request->dosen,
                'deskripsi' => $request->deskripsi,
                'user_id_pembuat' => Auth::user()->id
            ]);
        }

        return Prodi::create([
            'tipe' => $request->tipe,
            'mata_kuliah' => $request->mata_kuliah,
            'sks' => $request->sks,
            'kelas' => $request->kelas,
            'semester' => $request->semester,
            'dosen' => $request->dosen,
            'deskripsi' => $request->deskripsi,
            'user_id_pembuat' => Auth::user()->id
        ]);
    }
}
