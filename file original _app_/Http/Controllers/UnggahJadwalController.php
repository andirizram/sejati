<?php

namespace App\Http\Controllers;

use App\Models\Jadwal\Jadwal;
use App\Models\Jadwal\Lain;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalProdiImport;
use App\Imports\JadwalTPBImport;
use App\Imports\JadwalTAImport;
use App\Imports\JadwalLainImport;

class UnggahJadwalController extends Controller
{

    public function index()
    {
        $categories = Jadwal::CATEGORIES;

        return view('unggah_jadwal', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xlsm|max:2048',
            'category' => 'required|in:' . implode(',', Jadwal::CATEGORIES),
        ]);

        try {
            $uploadedSchedules = Jadwal::buatDariFile($request->file('file'), $request->input('category'), auth()->user());

            // Count the uploaded schedules
            $count = $uploadedSchedules->count();
            $category = $request->input('category');

            $successMessage = "Berhasil mengunggah {$count} jadwal ke dalam jadwal {$category}.";

            return redirect()->route('jadwal-' . strtolower($category) . '.index')->with('success', $successMessage);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Format file salah atau file rusak. Silahkan cek dan coba lagi.');
        }
    }


    protected function getImportClass($category)
    {
        // Return the appropriate import class based on the category
        return match ($category) {
            'prodi' => JadwalProdiImport::class,
            'tpb' => JadwalTPBImport::class,
            'ta' => JadwalTAImport::class,
            'lainnya' => JadwalLainImport::class,
            default => abort(404, 'Invalid category provided'),
        };
    }

    protected function getModelClass($category)
    {
        // Return the appropriate model class based on the category
        return match ($category) {
            'prodi' => \App\Models\Jadwal\Prodi::class,
            'tpb' => \App\Models\Jadwal\TPB::class,
            'ta' => \App\Models\Jadwal\TA::class,
            'lainnya' => \App\Models\Jadwal\Lain::class,
            default => abort(404, 'Category not found'),
        };
    }
}
