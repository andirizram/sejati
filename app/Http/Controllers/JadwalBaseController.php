<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJadwalRequest;
use App\Http\Requests\UpdateJadwalRequest;
use App\Http\Resources\JadwalResource;
use App\Models\Jadwal\Jadwal;
use App\Events\ScheduleUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class JadwalBaseController extends Controller
{
    protected string $model = '';

    protected string $module = '';

    protected array $columns = [];

    protected function refreshCollisionCache()
    {
        Cache::remember('schedule_collisions', now()->addMinutes(30), function () {
            return Jadwal::getTabrakan();
        });
    }

    public function index()
    {
        $columns = $this->columns;
        $resource = 'App\Http\Resources\Jadwal' . ucfirst(strtolower($this->module)) . 'Resource';
        $jadwals = Jadwal::with('detailJadwal')->where('kelas_detail_jadwal', $this->model)->get();
        $jadwals = $resource::collection($jadwals)->toArray(request());

        return view('jadwal-' . strtolower($this->module) . '.index', compact('jadwals', 'columns'));
    }

    public function store(StoreJadwalRequest $request)
    {
        $controller = get_class($this);
        $controller = new $controller();

        $formRequestClass = 'App\Http\Requests\Store' . ucfirst(strtolower($this->module)) . 'Request';

        // Validate the request
        try {
            app($formRequestClass)->validated();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->with('openModal', true)->withInput();
        }

        DB::beginTransaction();

        try {
            $createdType = $controller->save($request);

            Jadwal::create([
                'tanggal_mulai' => isset($request->pengulangan) ? Carbon::parseFromLocale($request->hari) : Carbon::parseFromLocale($request->tanggal),
                'waktu_mulai' => Jadwal::parseDateTime($request->waktu_mulai),
                'waktu_selesai' => Jadwal::parseDateTime($request->waktu_selesai),
                'ruangan' => $request->ruangan,
                'pengulangan' => isset($request->pengulangan) ? 1 : 0,
                'id_detail_jadwal' => $createdType->id,
                'kelas_detail_jadwal' => $this->model,
                'user_id_pembuat' => Auth::user()->id
            ]);

            DB::commit();

            // Clear cache
            Cache::forget('schedule_collisions');
            $this->refreshCollisionCache();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->with('error', $e->getMessage());
        }
        // After committing the transaction
        event(new ScheduleUpdated($createdType));

        return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->withSuccess('Data berhasil ditambahkan');
    }

    public function show($id)
    {
        $query = Jadwal::with('detailJadwal')->where('id', $id);

        if ($this->model) {
            $query->where('kelas_detail_jadwal', $this->model);
        }

        $jadwal = $query->first();

        return response()->json(new JadwalResource($jadwal, $this->model));
    }

    public function update(UpdateJadwalRequest $request, $id)
    {
        $controller = get_class($this);
        $controller = new $controller();

        $formRequestClass = 'App\Http\Requests\Update' . ucfirst(strtolower($this->module)) . 'Request';

        // Validate the request
        try {
            app($formRequestClass)->validated();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->with('openModal', true)->withInput()->with('error', 'Gagal memperbaharui karena ada data yang salah');
        }

        DB::beginTransaction();

        try {
            $jadwal = Jadwal::where('id', $id)->where('kelas_detail_jadwal', $this->model)->first();

            if (!$jadwal) {
                return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->with('error', 'Data tidak ditemukan');
            }

            if (Auth::user()->can('profile.edit')) {
                $updatedType = $controller->save($request, $jadwal->id_detail_jadwal);

                $jadwal->update([
                    'tanggal_mulai' => isset($request->pengulangan) ? Carbon::parseFromLocale($request->hari) : Carbon::parseFromLocale($request->tanggal),
                    'waktu_mulai' => Jadwal::parseDateTime($request->waktu_mulai),
                    'waktu_selesai' => Jadwal::parseDateTime($request->waktu_selesai),
                    'ruangan' => $request->ruangan,
                    'pengulangan' => isset($request->pengulangan) ? 1 : 0,
                ]);
            } else {
                $jadwal->detailJadwal->update(['deskripsi' => $request->deskripsi]);
            }

            DB::commit();

            // Clear cache
            Cache::forget('schedule_collisions');
            $this->refreshCollisionCache();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->with('error', $e->getMessage());
        }
        // After committing the transaction
        event(new ScheduleUpdated($jadwal));

        return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->withSuccess('Data berhasil diubah');
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('jadwal-' . strtolower($this->module) . '.destroy')) {
            return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->with('error', 'Anda tidak memiliki akses');
        }

        $jadwal = Jadwal::where('id', $id)->where('kelas_detail_jadwal', $this->model)->first();

        if (!$jadwal) {
            return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->with('error', 'Data tidak ditemukan');
        }

        // Manually delete entries from user_mahasiswa_has_jadwals
        DB::table('user_mahasiswa_has_jadwals')->where('jadwal_id', $id)->delete();

        // Manually delete entries from perubahan_jadwals
        DB::table('perubahan_jadwals')->where('jadwal_id', $id)->delete();

        // Check existence and delete detailJadwal if it exists
        if ($jadwal->detailJadwal) {
            $jadwal->detailJadwal->delete();
        }

        // Now it's safe to delete the Jadwal
        $jadwal->delete();

        // Clear cache
        Cache::forget('schedule_collisions');
        $this->refreshCollisionCache();

        return redirect()->route('jadwal-' . strtolower($this->module) . '.index')->withSuccess('Data berhasil dihapus');
    }
}
