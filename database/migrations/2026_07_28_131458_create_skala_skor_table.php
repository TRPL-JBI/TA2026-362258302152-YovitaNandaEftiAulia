<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skala_skor', function (Blueprint $table) {
            $table->id();

            $table->string(
                'label_skor',
                100
            );

            $table->unsignedTinyInteger(
                'nilai_skor'
            );

            $table->timestamps();

            $table->unique('label_skor');
            $table->unique('nilai_skor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skala_skor');
    }
};