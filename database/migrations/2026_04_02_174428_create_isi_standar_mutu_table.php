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
    Schema::create('isi_standar_mutu', function (Blueprint $table) {
        $table->id();

        // relasi ke standar_mutu
        $table->foreignId('id_standar_mutu')
              ->constrained('standar_mutu')
              ->cascadeOnDelete();

        $table->string('nama_standar');

        // parent (hirarki)
        $table->foreignId('parent_standar_id')
              ->nullable()
              ->constrained('isi_standar_mutu')
              ->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isi_standar_mutu');
    }
};
