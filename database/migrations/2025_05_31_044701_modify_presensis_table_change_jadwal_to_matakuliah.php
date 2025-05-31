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
        Schema::table('presensis', function (Blueprint $table) {
            // Add new matakuliah_id column with foreign key
            $table->foreignId('matakuliah_id')
                ->after('id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            // Drop the foreign key constraint and column
            $table->dropForeign(['matakuliah_id']);
            $table->dropColumn('matakuliah_id');
        });
    }
};
