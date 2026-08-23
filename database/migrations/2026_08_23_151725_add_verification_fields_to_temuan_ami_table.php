<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temuan_ami', function (Blueprint $table) {
            $table->unsignedBigInteger('verified_by')
                ->nullable()
                ->after('status_temuan');

            $table->timestamp('verified_at')
                ->nullable()
                ->after('verified_by');

            $table->unsignedBigInteger('closed_by')
                ->nullable()
                ->after('verified_at');

            $table->timestamp('closed_at')
                ->nullable()
                ->after('closed_by');

            $table->text('verification_note')
                ->nullable()
                ->after('closed_at');
        });
    }

    public function down(): void
    {
        Schema::table('temuan_ami', function (Blueprint $table) {
            $table->dropColumn([
                'verified_by',
                'verified_at',
                'closed_by',
                'closed_at',
                'verification_note',
            ]);
        });
    }
};