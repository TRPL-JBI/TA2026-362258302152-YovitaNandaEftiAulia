<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'skor_penerapan_standar',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId(
                    'id_penerapan_standar'
                )
                    ->constrained(
                        'penerapan_standar'
                    )
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreignId(
                    'id_skala_skor'
                )
                    ->constrained(
                        'skala_skor'
                    )
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->timestamps();

                /*
                 * Satu data penerapan standar hanya memiliki
                 * satu skor.
                 */
                $table->unique(
                    'id_penerapan_standar',
                    'skor_penerapan_unique'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'skor_penerapan_standar'
        );
    }
};