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
    Schema::create('standarmutu_periodeami', function (Blueprint $table) {
        $table->id();

        // relasi ke standar_mutu
        $table->foreignId('id_standar_mutu')
              ->constrained('standar_mutu');

        // relasi ke periode_ami
        $table->foreignId('id_periode_ami')
              ->constrained('periode_ami');

        $table->enum('status', ['aktif','tidak_aktif']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standarmutu_periodeami');
    }
};
