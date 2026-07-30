<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'rekomendasi_peningkatan',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id_temuan')
                    ->nullable()
                    ->after('id');

                $table->foreign('id_temuan')
                    ->references('id')
                    ->on('temuan_ami')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'rekomendasi_peningkatan',
            function (Blueprint $table) {
                $table->dropForeign(['id_temuan']);
                $table->dropColumn('id_temuan');
            }
        );
    }
};