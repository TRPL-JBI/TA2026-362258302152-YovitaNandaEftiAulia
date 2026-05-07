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
    Schema::create('indikator_standar', function (Blueprint $table) {
        $table->id();

        // relasi ke isi_standar_mutu
        $table->foreignId('id_isi_standar_mutu')
              ->constrained('isi_standar_mutu')
              ->cascadeOnDelete();

        $table->text('deskripsi');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_standar');
    }
};
