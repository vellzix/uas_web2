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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('no_hp')->after('nama');
            $table->string('tempat_lahir')->after('no_hp');
            $table->date('tanggal_lahir')->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['L', 'P'])->after('tanggal_lahir');
            $table->string('agama')->after('jenis_kelamin');
            $table->dropColumn('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->integer('semester');
            $table->dropColumn([
                'no_hp',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama'
            ]);
        });
    }
};
