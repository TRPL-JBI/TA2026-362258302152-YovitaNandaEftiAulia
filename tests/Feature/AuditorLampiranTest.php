<?php

namespace Tests\Feature;

use App\Models\LampiranAudit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditorLampiranTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabel_lampiran_audit_memiliki_struktur_utama(): void
    {
        $this->assertTrue(
            Schema::hasTable('lampiran_audit')
        );

        $this->assertTrue(
            Schema::hasColumns(
                'lampiran_audit',
                [
                    'id',
                    'id_periode_ami',
                    'id_user',
                ]
            )
        );
    }

    public function test_lampiran_audit_berada_pada_level_periode(): void
    {
        $this->assertTrue(
            Schema::hasColumn(
                'lampiran_audit',
                'id_periode_ami'
            )
        );

        $this->assertFalse(
            Schema::hasColumn(
                'lampiran_audit',
                'id_penerapan_standar'
            ),
            'Lampiran Audit seharusnya tetap berada pada level Periode AMI.'
        );

        $this->assertFalse(
            Schema::hasColumn(
                'lampiran_audit',
                'id_indikator'
            ),
            'Lampiran Audit tidak seharusnya langsung terhubung ke indikator.'
        );
    }

    public function test_model_lampiran_menggunakan_tabel_yang_benar(): void
    {
        $model = new LampiranAudit();

        $this->assertSame(
            'lampiran_audit',
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

    public function test_route_crud_lampiran_auditor_tersedia(): void
    {
        $routes = [
            'auditor.lampiran.index',
            'auditor.lampiran.create',
            'auditor.lampiran.store',
            'auditor.lampiran.show',
            'auditor.lampiran.edit',
            'auditor.lampiran.update',
            'auditor.lampiran.destroy',
        ];

        foreach ($routes as $route) {
            $this->assertTrue(
                Route::has($route),
                "Route {$route} tidak ditemukan."
            );
        }
    }
}