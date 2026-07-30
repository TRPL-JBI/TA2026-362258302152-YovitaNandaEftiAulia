<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Menghubungkan rekomendasi lama dengan temuan
         * berdasarkan id_penerapan_standar.
         *
         * MIN(id) dipakai untuk data lama karena sebelumnya
         * rekomendasi belum terhubung langsung ke temuan.
         */
        DB::statement("
            UPDATE rekomendasi_peningkatan AS rp
            SET rp.id_temuan = (
                SELECT MIN(ta.id)
                FROM temuan_ami AS ta
                WHERE ta.id_penerapan_standar =
                      rp.id_penerapan_standar
                  AND ta.deleted_at IS NULL
            )
            WHERE rp.id_temuan IS NULL
        ");
    }

    public function down(): void
    {
        DB::table('rekomendasi_peningkatan')
            ->update([
                'id_temuan' => null,
            ]);
    }
};