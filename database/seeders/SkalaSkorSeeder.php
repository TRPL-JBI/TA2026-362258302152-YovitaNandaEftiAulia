<?php

namespace Database\Seeders;

use App\Models\SkalaSkor;
use Illuminate\Database\Seeder;

class SkalaSkorSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'label_skor' => 'Tidak Terpenuhi',
                'nilai_skor' => 1,
            ],
            [
                'label_skor' => 'Kurang Terpenuhi',
                'nilai_skor' => 2,
            ],
            [
                'label_skor' => 'Cukup Terpenuhi',
                'nilai_skor' => 3,
            ],
            [
                'label_skor' => 'Terpenuhi',
                'nilai_skor' => 4,
            ],
            [
                'label_skor' => 'Sangat Terpenuhi',
                'nilai_skor' => 5,
            ],
        ];

        foreach ($data as $item) {
            SkalaSkor::updateOrCreate(
                [
                    'nilai_skor' =>
                        $item['nilai_skor'],
                ],
                [
                    'label_skor' =>
                        $item['label_skor'],
                ]
            );
        }
    }
}