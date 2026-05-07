<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('periode_ami', function (Blueprint $table) {
        $table->id();

        $table->year('tahun');

        // relasi
        $table->foreignId('id_standar_mutu')->constrained('standar_mutu');
        $table->foreignId('id_unit_kerja')->constrained('unit_kerja');
        $table->foreignId('id_user')->constrained('users');

        $table->text('tujuan_audit');
        $table->text('lingkup_audit');
        $table->string('waktu_audit');

        $table->date('tanggal_buka_ami');
        $table->date('tanggal_tutup_ami');

        $table->enum('status', ['draft','berjalan','ditutup']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_ami');
    }
};
