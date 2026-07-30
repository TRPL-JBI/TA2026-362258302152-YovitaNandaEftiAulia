<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temuan_ami', function (Blueprint $table) {
            $table->text('temuan')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('temuan_ami', function (Blueprint $table) {
            $table->text('temuan')
                ->nullable(false)
                ->change();
        });
    }
};