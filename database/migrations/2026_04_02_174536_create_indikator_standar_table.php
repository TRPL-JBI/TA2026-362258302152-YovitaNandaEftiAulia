<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_standar', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_isi_standar_mutu')
                  ->constrained('isi_standar_mutu')
                  ->cascadeOnDelete();

            $table->text('deskripsi');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_standar');
    }
};