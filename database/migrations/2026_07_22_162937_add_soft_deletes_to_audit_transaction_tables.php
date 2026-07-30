<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom deleted_at ke tabel transaksi audit.
     */
    public function up(): void
    {
        if (
            Schema::hasTable('penerapan_standar')
            && !Schema::hasColumn('penerapan_standar', 'deleted_at')
        ) {
            Schema::table(
                'penerapan_standar',
                function (Blueprint $table) {
                    $table->softDeletes();
                }
            );
        }

        if (
            Schema::hasTable('temuan_ami')
            && !Schema::hasColumn('temuan_ami', 'deleted_at')
        ) {
            Schema::table(
                'temuan_ami',
                function (Blueprint $table) {
                    $table->softDeletes();
                }
            );
        }

        if (
            Schema::hasTable('tanggapan_auditee')
            && !Schema::hasColumn('tanggapan_auditee', 'deleted_at')
        ) {
            Schema::table(
                'tanggapan_auditee',
                function (Blueprint $table) {
                    $table->softDeletes();
                }
            );
        }

        if (
            Schema::hasTable('akar_masalah')
            && !Schema::hasColumn('akar_masalah', 'deleted_at')
        ) {
            Schema::table(
                'akar_masalah',
                function (Blueprint $table) {
                    $table->softDeletes();
                }
            );
        }

        if (
            Schema::hasTable('rekomendasi_peningkatan')
            && !Schema::hasColumn(
                'rekomendasi_peningkatan',
                'deleted_at'
            )
        ) {
            Schema::table(
                'rekomendasi_peningkatan',
                function (Blueprint $table) {
                    $table->softDeletes();
                }
            );
        }

        if (
            Schema::hasTable('lampiran_audit')
            && !Schema::hasColumn('lampiran_audit', 'deleted_at')
        ) {
            Schema::table(
                'lampiran_audit',
                function (Blueprint $table) {
                    $table->softDeletes();
                }
            );
        }
    }

    /**
     * Hapus kolom deleted_at apabila migration di-rollback.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('lampiran_audit')
            && Schema::hasColumn('lampiran_audit', 'deleted_at')
        ) {
            Schema::table(
                'lampiran_audit',
                function (Blueprint $table) {
                    $table->dropSoftDeletes();
                }
            );
        }

        if (
            Schema::hasTable('rekomendasi_peningkatan')
            && Schema::hasColumn(
                'rekomendasi_peningkatan',
                'deleted_at'
            )
        ) {
            Schema::table(
                'rekomendasi_peningkatan',
                function (Blueprint $table) {
                    $table->dropSoftDeletes();
                }
            );
        }

        if (
            Schema::hasTable('akar_masalah')
            && Schema::hasColumn('akar_masalah', 'deleted_at')
        ) {
            Schema::table(
                'akar_masalah',
                function (Blueprint $table) {
                    $table->dropSoftDeletes();
                }
            );
        }

        if (
            Schema::hasTable('tanggapan_auditee')
            && Schema::hasColumn('tanggapan_auditee', 'deleted_at')
        ) {
            Schema::table(
                'tanggapan_auditee',
                function (Blueprint $table) {
                    $table->dropSoftDeletes();
                }
            );
        }

        if (
            Schema::hasTable('temuan_ami')
            && Schema::hasColumn('temuan_ami', 'deleted_at')
        ) {
            Schema::table(
                'temuan_ami',
                function (Blueprint $table) {
                    $table->dropSoftDeletes();
                }
            );
        }

        if (
            Schema::hasTable('penerapan_standar')
            && Schema::hasColumn('penerapan_standar', 'deleted_at')
        ) {
            Schema::table(
                'penerapan_standar',
                function (Blueprint $table) {
                    $table->dropSoftDeletes();
                }
            );
        }
    }
};