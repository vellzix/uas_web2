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
        Schema::table('krs', function (Blueprint $table) {
            // Add periode_akademik_id column
            $table->foreignId('periode_akademik_id')->after('jadwal_id')->constrained('periode_akademik')->onDelete('cascade');
            
            // Add unique constraint
            $table->unique(['mahasiswa_id', 'jadwal_id', 'periode_akademik_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            // Drop unique constraint
            $table->dropUnique(['mahasiswa_id', 'jadwal_id', 'periode_akademik_id']);
            
            // Drop foreign key and column
            $table->dropForeign(['periode_akademik_id']);
            $table->dropColumn('periode_akademik_id');
        });
    }
};
