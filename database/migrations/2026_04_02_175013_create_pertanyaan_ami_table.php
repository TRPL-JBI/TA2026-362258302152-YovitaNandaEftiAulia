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
    Schema::create('pertanyaan_ami', function (Blueprint $table) {
        $table->id();

        $table->text('pertanyaan');

        // relasi ke penerapan_standar
        $table->foreignId('id_penerapan_standar')
              ->constrained('penerapan_standar')
              ->cascadeOnDelete();

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
        Schema::dropIfExists('pertanyaan_ami');
    }
};
