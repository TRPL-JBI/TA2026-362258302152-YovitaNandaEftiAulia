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
    Schema::create('temuan_ami', function (Blueprint $table) {
        $table->id();

        // relasi ke pertanyaan
        $table->foreignId('id_pertanyaan')
              ->constrained('pertanyaan_ami')
              ->cascadeOnDelete();

        $table->text('temuan');

        $table->enum('status_temuan', ['open','closed']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temuan_ami');
    }
};
