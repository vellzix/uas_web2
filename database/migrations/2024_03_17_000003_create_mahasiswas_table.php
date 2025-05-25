<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('prodi_id')->constrained()->onDelete('restrict');
            $table->string('nim')->unique();
            $table->string('nama');
            $table->integer('semester');
            $table->year('angkatan');
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'do'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
}; 