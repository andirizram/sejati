<?php

use App\Models\Jadwal\Jadwal;
use App\Models\PerubahanJadwal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perubahan_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')
                ->constrained('jadwals')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('user_id_pembuat')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->tinyInteger('status')
                ->default(PerubahanJadwal::STATUS_TUNGGU);
            $table->foreignId('user_id_penindak')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('ruangan');
            $table->text('alasan');
            $table->text('file_pendukung')->nullable();
            $table->tinyInteger('pengulangan')
                ->default(Jadwal::TANPA_PENGULANGAN);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_jadwals');
    }
};
