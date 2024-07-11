<?php

namespace App\Http\Controllers;

use App\Models\Jadwal\Jadwal;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturans = Pengaturan::get();
        return view('pengaturan', compact('pengaturans'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tanggal_kuliah_terakhir' => ['required', 'numeric', 'digits:8', 'regex:/^20[2-9][0-9](0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])$/'],
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Pengaturan::where('key', $key)->update(['value' => $value]);
        }
        return redirect()->back()->withSuccess('Data berhasil diupdate!');
    }

    public function clear_data(Request $request)
    {
        $category = $request->input('category');
        $cleared = Jadwal::kosongkanData($category);

        if (!$cleared) {
            return redirect()->back()->withError('Data gagal dihapus!');
        }
        return redirect()->back()->withSuccess('Data berhasil dihapus!');
    }

}
