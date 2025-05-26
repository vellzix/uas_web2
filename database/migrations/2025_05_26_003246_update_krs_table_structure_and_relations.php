<?php

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
        // Drop old table
        Schema::dropIfExists('krs');
        
        // Create new table
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('jadwal_matakuliah_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('periode_akademik_id')->constrained('periode_akademik')->onDelete('cascade');
            $table->timestamps();
            
            // Add unique constraint
            $table->unique(['mahasiswa_id', 'jadwal_matakuliah_id', 'periode_akademik_id'], 'krs_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
