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
    Schema::create('tim_ami', function (Blueprint $table) {
        $table->id();

        // relasi ke periode
        $table->foreignId('id_periode_ami')
              ->constrained('periode_ami')
              ->cascadeOnDelete();

        // relasi ke user
        $table->foreignId('id_user')
              ->constrained('users');

        $table->enum('role', ['ketua auditor','auditor','auditee']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tim_ami');
    }
};
