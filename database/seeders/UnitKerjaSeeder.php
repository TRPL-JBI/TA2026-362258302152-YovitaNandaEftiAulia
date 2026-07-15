<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('unit_kerja')->updateOrInsert(
            ['id' => 1],
            [
                'nama' => 'Administrator',
                'kategori_unit_kerja' => 'Lainnya',
            ]
        );
    }
}