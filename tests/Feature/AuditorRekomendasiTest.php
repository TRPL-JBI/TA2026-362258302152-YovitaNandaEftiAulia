<?php

namespace Tests\Feature;

use App\Models\RekomendasiPeningkatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditorRekomendasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabel_rekomendasi_peningkatan_memiliki_struktur_utama(): void
    {
        $this->assertTrue(
            Schema::hasTable('rekomendasi_peningkatan')
        );

        $this->assertTrue(
            Schema::hasColumns(
                'rekomendasi_peningkatan',
                [
                    'id',
                    'id_penerapan_standar',
                    'id_user',
                ]
            )
        );
    }

    public function test_rekomendasi_terhubung_ke_penerapan_standar(): void
    {
        $this->assertTrue(
            Schema::hasColumn(
                'rekomendasi_peningkatan',
                'id_penerapan_standar'
            )
        );

        $this->assertFalse(
            Schema::hasColumn(
                'rekomendasi_peningkatan',
                'id_indikator'
            ),
            'Rekomendasi seharusnya memakai id_penerapan_standar, bukan id_indikator.'
        );
    }

    public function test_model_rekomendasi_menggunakan_tabel_yang_benar(): void
    {
        $model = new RekomendasiPeningkatan();

        $this->assertSame(
            'rekomendasi_peningkatan',
            $model->getTable()
        );

        $this->assertContains(
            'id_penerapan_standar',
            $model->getFillable()
        );

        $this->assertContains(
            'id_user',
            $model->getFillable()
        );
    }

    public function test_route_crud_rekomendasi_auditor_tersedia(): void
    {
        $routes = [
            'auditor.rekomendasi.index',
            'auditor.rekomendasi.create',
            'auditor.rekomendasi.store',
            'auditor.rekomendasi.show',
            'auditor.rekomendasi.edit',
            'auditor.rekomendasi.update',
            'auditor.rekomendasi.destroy',
        ];

        foreach ($routes as $route) {
            $this->assertTrue(
                Route::has($route),
                "Route {$route} tidak ditemukan."
            );
        }
    }
}