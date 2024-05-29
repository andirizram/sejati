<?php

use App\Models\Jadwal\Jadwal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_mulai');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('ruangan');
            $table->tinyInteger('pengulangan')
                ->default(Jadwal::TANPA_PENGULANGAN);
            $table->foreignId('id_detail_jadwal');
            $table->string('kelas_detail_jadwal');
            $table->foreignId('user_id_pembuat')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
