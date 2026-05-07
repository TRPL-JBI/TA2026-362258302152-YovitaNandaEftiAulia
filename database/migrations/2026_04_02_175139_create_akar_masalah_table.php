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
    Schema::create('akar_masalah', function (Blueprint $table) {
        $table->id();

        // relasi ke temuan_ami
        $table->foreignId('id_temuan')
              ->constrained('temuan_ami')
              ->cascadeOnDelete();

        $table->text('akar_masalah');

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
        Schema::dropIfExists('akar_masalah');
    }
};
