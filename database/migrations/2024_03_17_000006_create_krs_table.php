<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained()->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained()->onDelete('restrict');
            $table->string('semester');
            $table->string('tahun_ajaran');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Mencegah mahasiswa mengambil jadwal yang sama
            $table->unique(['mahasiswa_id', 'jadwal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
}; 