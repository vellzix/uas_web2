<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained()->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained()->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('komentar')->nullable();
            $table->enum('status', ['belum_dikumpulkan', 'submitted', 'dinilai', 'terlambat'])->default('belum_dikumpulkan');
            $table->datetime('waktu_pengumpulan')->nullable();
            $table->timestamps();

            // Mencegah duplikasi pengumpulan tugas
            $table->unique(['tugas_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_mahasiswas');
    }
}; 