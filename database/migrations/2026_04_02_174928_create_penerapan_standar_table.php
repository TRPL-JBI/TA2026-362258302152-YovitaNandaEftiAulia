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
    Schema::create('penerapan_standar', function (Blueprint $table) {
        $table->id();

        // relasi ke standarmutu_periodeami
        $table->foreignId('id_standarmutu_periodeami')
              ->constrained('standarmutu_periodeami')
              ->cascadeOnDelete();

        $table->text('deskripsi_hasil');
        $table->text('link_bukti')->nullable();

        // relasi ke user
        $table->foreignId('id_user')
              ->constrained('users');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerapan_standar');
    }
};
