<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('krs')->onDelete('cascade');
            $table->decimal('nilai_angka', 5, 2);
            $table->string('nilai_huruf', 2);
            $table->decimal('nilai_indeks', 3, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['krs_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
}; 