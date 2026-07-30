<?php

namespace Tests\Feature;

use App\Models\KesimpulanAudit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditorKesimpulanTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabel_kesimpulan_audit_memiliki_struktur_utama(): void
    {
        $this->assertTrue(
            Schema::hasTable('kesimpulan_audit')
        );

        $this->assertTrue(
            Schema::hasColumns(
                'kesimpulan_audit',
                [
                    'id',
                    'id_periode_ami',
                    'id_user',
                ]
            )
        );
    }

    public function test_kesimpulan_audit_berada_pada_level_periode(): void
    {
        $this->assertTrue(
            Schema::hasColumn(
                'kesimpulan_audit',
                'id_periode_ami'
            )
        );

        $this->assertFalse(
            Schema::hasColumn(
                'kesimpulan_audit',
                'id_penerapan_standar'
            ),
            'Kesimpulan Audit seharusnya tetap berada pada level Periode AMI.'
        );

        $this->assertFalse(
            Schema::hasColumn(
                'kesimpulan_audit',
                'id_indikator'
            ),
            'Kesimpulan Audit tidak seharusnya langsung terhubung ke indikator.'
        );
    }

    public function test_model_kesimpulan_menggunakan_tabel_yang_benar(): void
    {
        $model = new KesimpulanAudit();

        $this->assertSame(
            'kesimpulan_audit',
            $model->getTable()
        );

        $this->assertContains(
            'id_periode_ami',
            $model->getFillable()
        );

        $this->assertContains(
            'id_user',
            $model->getFillable()
        );
    }

    public function test_route_crud_kesimpulan_auditor_tersedia(): void
    {
        $routes = [
            'auditor.kesimpulan.index',
            'auditor.kesimpulan.create',
            'auditor.kesimpulan.store',
            'auditor.kesimpulan.show',
            'auditor.kesimpulan.edit',
            'auditor.kesimpulan.update',
            'auditor.kesimpulan.destroy',
        ];

        foreach ($routes as $route) {
            $this->assertTrue(
                Route::has($route),
                "Route {$route} tidak ditemukan."
            );
        }
    }
}