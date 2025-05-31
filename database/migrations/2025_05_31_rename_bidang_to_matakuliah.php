<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // Drop the existing bidang column
            $table->dropColumn('bidang');
            
            // Add new matakuliah_id column
            $table->foreignId('matakuliah_id')
                ->nullable()
                ->constrained('matakuliahs')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // Remove the foreign key and column
            $table->dropForeign(['matakuliah_id']);
            $table->dropColumn('matakuliah_id');
            
            // Add back the original bidang column
            $table->string('bidang');
        });
    }
}; 