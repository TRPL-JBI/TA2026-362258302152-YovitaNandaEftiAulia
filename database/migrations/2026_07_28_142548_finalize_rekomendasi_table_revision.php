<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Hapus relasi lama dari rekomendasi_peningkatan
         * menuju penerapan_standar.
         */
        Schema::table(
            'rekomendasi_peningkatan',
            function (Blueprint $table) {
                $table->dropForeign([
                    'id_penerapan_standar',
                ]);

                $table->dropColumn(
                    'id_penerapan_standar'
                );
            }
        );

        /*
         * id_temuan sekarang wajib diisi.
         */
        Schema::table(
            'rekomendasi_peningkatan',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id_temuan')
                    ->nullable(false)
                    ->change();
            }
        );

        /*
         * Nama tabel dibuat lebih umum karena tabel ini
         * menyimpan rekomendasi perbaikan dan peningkatan.
         */
        Schema::rename(
            'rekomendasi_peningkatan',
            'rekomendasi'
        );
    }

    public function down(): void
    {
        Schema::rename(
            'rekomendasi',
            'rekomendasi_peningkatan'
        );

        Schema::table(
            'rekomendasi_peningkatan',
            function (Blueprint $table) {
                $table->unsignedBigInteger(
                    'id_penerapan_standar'
                )
                    ->nullable()
                    ->after('id_temuan');

                $table->foreign(
                    'id_penerapan_standar'
                )
                    ->references('id')
                    ->on('penerapan_standar')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->unsignedBigInteger('id_temuan')
                    ->nullable()
                    ->change();
            }
        );
    }
};