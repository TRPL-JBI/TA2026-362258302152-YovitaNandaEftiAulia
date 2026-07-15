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
        Schema::table('penerapan_standar', function (Blueprint $table) {

            $table->unsignedBigInteger('id_indikator')
                  ->after('id_standarmutu_periodeami');

            $table->foreign('id_indikator')
                  ->references('id')
                  ->on('indikator_standar')
                  ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerapan_standar', function (Blueprint $table) {

            $table->dropForeign(['id_indikator']);

            $table->dropColumn('id_indikator');

        });
    }
};