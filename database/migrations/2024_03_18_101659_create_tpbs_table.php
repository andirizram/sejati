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
        Schema::create('tpbs', function (Blueprint $table) {
            $table->id();
            $table->string('tipe');
            $table->string('mata_kuliah');
            $table->integer('sks');
            $table->integer('semester');
            $table->string('dosen');
            $table->string('kelas');
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
        Schema::dropIfExists('tpbs');
    }
};
