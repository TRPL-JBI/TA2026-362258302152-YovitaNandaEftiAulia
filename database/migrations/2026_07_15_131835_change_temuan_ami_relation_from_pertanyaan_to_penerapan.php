<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('temuan_ami')) {
            return;
        }

        if (
            !Schema::hasColumn(
                'temuan_ami',
                'id_penerapan_standar'
            )
        ) {
            Schema::table(
                'temuan_ami',
                function (Blueprint $table) {
                    $table
                        ->unsignedBigInteger(
                            'id_penerapan_standar'
                        )
                        ->nullable()
                        ->after('id');
                }
            );
        }

        if (
            Schema::hasTable('pertanyaan_ami')
            && Schema::hasColumn(
                'temuan_ami',
                'id_pertanyaan'
            )
        ) {
            $pertanyaanList = DB::table(
                'pertanyaan_ami'
            )
                ->select([
                    'id',
                    'id_penerapan_standar',
                ])
                ->whereNotNull(
                    'id_penerapan_standar'
                )
                ->get();

            foreach ($pertanyaanList as $pertanyaan) {
                DB::table('temuan_ami')
                    ->where(
                        'id_pertanyaan',
                        $pertanyaan->id
                    )
                    ->update([
                        'id_penerapan_standar' =>
                            $pertanyaan
                                ->id_penerapan_standar,
                    ]);
            }
        }

        if (
            Schema::hasColumn(
                'temuan_ami',
                'id_pertanyaan'
            )
        ) {
            try {
                Schema::table(
                    'temuan_ami',
                    function (Blueprint $table) {
                        $table->dropForeign([
                            'id_pertanyaan',
                        ]);
                    }
                );
            } catch (\Throwable $exception) {
                // Foreign key mungkin tidak tersedia.
            }

            Schema::table(
                'temuan_ami',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'id_pertanyaan'
                    );
                }
            );
        }

        if (
            Schema::hasTable('penerapan_standar')
            && Schema::hasColumn(
                'temuan_ami',
                'id_penerapan_standar'
            )
        ) {
            try {
                Schema::table(
                    'temuan_ami',
                    function (Blueprint $table) {
                        $table
                            ->foreign(
                                'id_penerapan_standar'
                            )
                            ->references('id')
                            ->on('penerapan_standar')
                            ->cascadeOnUpdate()
                            ->cascadeOnDelete();
                    }
                );
            } catch (\Throwable $exception) {
                // Foreign key mungkin sudah tersedia.
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('temuan_ami')) {
            return;
        }

        if (
            !Schema::hasColumn(
                'temuan_ami',
                'id_pertanyaan'
            )
        ) {
            Schema::table(
                'temuan_ami',
                function (Blueprint $table) {
                    $table
                        ->unsignedBigInteger(
                            'id_pertanyaan'
                        )
                        ->nullable()
                        ->after('id');
                }
            );
        }

        if (
            Schema::hasTable('pertanyaan_ami')
            && Schema::hasColumn(
                'temuan_ami',
                'id_penerapan_standar'
            )
        ) {
            $temuanList = DB::table('temuan_ami')
                ->select([
                    'id',
                    'id_penerapan_standar',
                ])
                ->whereNotNull(
                    'id_penerapan_standar'
                )
                ->get();

            foreach ($temuanList as $temuan) {
                $idPertanyaan = DB::table(
                    'pertanyaan_ami'
                )
                    ->where(
                        'id_penerapan_standar',
                        $temuan->id_penerapan_standar
                    )
                    ->orderBy('id')
                    ->value('id');

                if ($idPertanyaan !== null) {
                    DB::table('temuan_ami')
                        ->where('id', $temuan->id)
                        ->update([
                            'id_pertanyaan' =>
                                $idPertanyaan,
                        ]);
                }
            }
        }

        if (
            Schema::hasColumn(
                'temuan_ami',
                'id_penerapan_standar'
            )
        ) {
            try {
                Schema::table(
                    'temuan_ami',
                    function (Blueprint $table) {
                        $table->dropForeign([
                            'id_penerapan_standar',
                        ]);
                    }
                );
            } catch (\Throwable $exception) {
                // Foreign key mungkin tidak tersedia.
            }

            Schema::table(
                'temuan_ami',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'id_penerapan_standar'
                    );
                }
            );
        }

        if (
            Schema::hasTable('pertanyaan_ami')
            && Schema::hasColumn(
                'temuan_ami',
                'id_pertanyaan'
            )
        ) {
            try {
                Schema::table(
                    'temuan_ami',
                    function (Blueprint $table) {
                        $table
                            ->foreign('id_pertanyaan')
                            ->references('id')
                            ->on('pertanyaan_ami')
                            ->cascadeOnUpdate()
                            ->cascadeOnDelete();
                    }
                );
            } catch (\Throwable $exception) {
                // Foreign key mungkin sudah tersedia.
            }
        }
    }
};