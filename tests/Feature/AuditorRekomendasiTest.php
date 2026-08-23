<?php

namespace Tests\Feature;

use App\Models\Rekomendasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditorRekomendasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabel_rekomendasi_memiliki_struktur_utama(): void
    {
        $this->assertTrue(
            Schema::hasTable('rekomendasi')
        );

        $this->assertTrue(
            Schema::hasColumns(
                'rekomendasi',
                [
                    'id',
                    'id_temuan',
                    'aspek',
                    'deskripsi',
                    'rekomendasi',
                    'id_user',
                ]
            )
        );
    }

    public function test_rekomendasi_terhubung_ke_temuan(): void
    {
        $this->assertTrue(
            Schema::hasColumn(
                'rekomendasi',
                'id_temuan'
            )
        );

        $this->assertFalse(
            Schema::hasColumn(
                'rekomendasi',
                'id_penerapan_standar'
            ),
            'Rekomendasi seharusnya terhubung ke temuan melalui id_temuan.'
        );

        $this->assertFalse(
            Schema::hasColumn(
                'rekomendasi',
                'id_indikator'
            ),
            'Rekomendasi seharusnya memakai id_temuan, bukan id_indikator.'
        );
    }

    public function test_model_rekomendasi_menggunakan_tabel_yang_benar(): void
    {
        $model = new Rekomendasi();

        $this->assertSame(
            'rekomendasi',
            $model->getTable()
        );

        $this->assertContains(
            'id_temuan',
            $model->getFillable()
        );

        $this->assertContains(
            'aspek',
            $model->getFillable()
        );

        $this->assertContains(
            'deskripsi',
            $model->getFillable()
        );

        $this->assertContains(
            'rekomendasi',
            $model->getFillable()
        );

        $this->assertContains(
            'id_user',
            $model->getFillable()
        );
    }
}