<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->decimal('nilai_uts', 5, 2)->nullable()->after('krs_id');
            $table->decimal('nilai_uas', 5, 2)->nullable()->after('nilai_uts');
            $table->decimal('nilai_tugas', 5, 2)->nullable()->after('nilai_uas');
        });
    }

    public function down()
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn(['nilai_uts', 'nilai_uas', 'nilai_tugas']);
        });
    }
}; 