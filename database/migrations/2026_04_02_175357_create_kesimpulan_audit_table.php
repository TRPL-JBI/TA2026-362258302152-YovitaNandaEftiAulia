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
    Schema::create('kesimpulan_audit', function (Blueprint $table) {
        $table->id();

        // relasi ke periode_ami
        $table->foreignId('id_periode_ami')
              ->constrained('periode_ami');

        // relasi ke user
        $table->foreignId('id_user')
              ->constrained('users');

        $table->text('kesimpulan');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kesimpulan_audit');
    }
};
