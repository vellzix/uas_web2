<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matakuliahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained()->onDelete('restrict');
            $table->string('kode')->unique();
            $table->string('nama');
            $table->integer('sks');
            $table->integer('semester');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // Tabel untuk prerequisite matakuliah
        Schema::create('matakuliah_prerequisite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matakuliah_id')->constrained()->onDelete('cascade');
            $table->foreignId('prerequisite_id')->constrained('matakuliahs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matakuliah_prerequisite');
        Schema::dropIfExists('matakuliahs');
    }
}; 