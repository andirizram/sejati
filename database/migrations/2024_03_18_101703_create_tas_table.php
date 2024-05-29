<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tas', function (Blueprint $table) {
            $table->id();
            $table->string('tipe');
            $table->string('nama_mahasiswa');
            $table->string('nim');
            $table->string('dosen_pembimbing_1');
            $table->string('dosen_pembimbing_2');
            $table->string('judul');
            $table->string('dosen_penguji_1');
            $table->string('dosen_penguji_2');
            $table->string('tautan');
            $table->text('deskripsi')
                ->nullable();
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
        Schema::dropIfExists('tas');
    }
};
