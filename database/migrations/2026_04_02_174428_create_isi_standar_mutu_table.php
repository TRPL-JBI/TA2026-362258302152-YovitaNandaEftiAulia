<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('isi_standar_mutu', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_standar_mutu')
                  ->constrained('standar_mutu')
                  ->cascadeOnDelete();

            $table->string('nama_standar');

            $table->unsignedBigInteger('parent_standar_id')
                  ->nullable();

            $table->timestamps();

            $table->foreign('parent_standar_id')
                  ->references('id')
                  ->on('isi_standar_mutu')
                  ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('isi_standar_mutu');
    }
};