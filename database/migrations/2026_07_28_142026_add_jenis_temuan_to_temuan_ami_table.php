<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temuan_ami', function (Blueprint $table) {
            $table->enum(
                'jenis_temuan',
                [
                    'sesuai_standar',
                    'kts',
                    'ob',
                ]
            )
                ->nullable()
                ->after('id_penerapan_standar');
        });

        /*
         * Data lama belum memiliki jenis temuan.
         * Untuk sementara diisi KTS karena sebelumnya
         * seluruh data pada tabel ini merupakan temuan audit.
         */
        DB::table('temuan_ami')
            ->whereNull('jenis_temuan')
            ->update([
                'jenis_temuan' => 'kts',
            ]);
    }

    public function down(): void
    {
        Schema::table('temuan_ami', function (Blueprint $table) {
            $table->dropColumn('jenis_temuan');
        });
    }
};