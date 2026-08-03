<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_ami_unit_kerja', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_periode_ami');
            $table->unsignedBigInteger('id_unit_kerja');

            $table->foreign('id_periode_ami')
                ->references('id')
                ->on('periode_ami')
                ->cascadeOnDelete();

            $table->foreign('id_unit_kerja')
                ->references('id')
                ->on('unit_kerja')
                ->cascadeOnDelete();

            $table->unique(
                [
                    'id_periode_ami',
                    'id_unit_kerja',
                ],
                'periode_ami_unit_kerja_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | PINDAHKAN RELASI DATA LAMA
        |--------------------------------------------------------------------------
        |
        | Periode lama yang sudah mempunyai id_unit_kerja akan langsung
        | dimasukkan ke tabel penghubung.
        |
        */

        $periodeLama = DB::table('periode_ami')
            ->whereNotNull('id_unit_kerja')
            ->select('id', 'id_unit_kerja')
            ->get();

        foreach ($periodeLama as $periode) {
            DB::table('periode_ami_unit_kerja')
                ->insertOrIgnore([
                    'id_periode_ami' => $periode->id,
                    'id_unit_kerja' => $periode->id_unit_kerja,
                ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_ami_unit_kerja');
    }
};