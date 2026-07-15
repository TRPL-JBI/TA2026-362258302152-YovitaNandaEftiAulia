<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed admin awal.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'vina@example.com',
            ],
            [
                'nama'            => 'Vina',
                'email'           => 'vina@example.com',
                'password'        => Hash::make('12345678'),
                'role'            => 'admin',
                'status'          => 'aktif',
                'id_unit_kerja'   => 1,
            ]
        );
    }
}