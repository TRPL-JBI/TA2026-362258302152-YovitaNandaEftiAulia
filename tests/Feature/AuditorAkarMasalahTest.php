<?php

namespace Tests\Feature;

use App\Models\AkarMasalah;
use App\Models\TemuanAmi;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditorAkarMasalahTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabel_akar_masalah_memiliki_struktur_yang_diperlukan(): void
    {
        $this->assertTrue(
            Schema::hasTable('akar_masalah')
        );

        $this->assertTrue(
            Schema::hasColumns('akar_masalah', [
                'id',
                'id_temuan',
                'akar_masalah',
                'id_user',
            ])
        );
    }

    public function test_model_akar_masalah_menggunakan_tabel_dan_fillable_yang_benar(): void
    {
        $model = new AkarMasalah();

        $this->assertSame(
            'akar_masalah',
            $model->getTable()
        );

        $this->assertContains(
            'id_temuan',
            $model->getFillable()
        );

        $this->assertContains(
            'akar_masalah',
            $model->getFillable()
        );

        $this->assertContains(
            'id_user',
            $model->getFillable()
        );
    }

    public function test_relasi_akar_masalah_ke_temuan_menggunakan_id_temuan(): void
    {
        $model = new AkarMasalah();

        $relation = $model->temuan();

        $this->assertInstanceOf(
            BelongsTo::class,
            $relation
        );

        $this->assertInstanceOf(
            TemuanAmi::class,
            $relation->getRelated()
        );

        $this->assertSame(
            'id_temuan',
            $relation->getForeignKeyName()
        );
    }

    public function test_relasi_akar_masalah_ke_user_menggunakan_id_user(): void
    {
        $model = new AkarMasalah();

        $relation = $model->user();

        $this->assertInstanceOf(
            BelongsTo::class,
            $relation
        );

        $this->assertInstanceOf(
            User::class,
            $relation->getRelated()
        );

        $this->assertSame(
            'id_user',
            $relation->getForeignKeyName()
        );
    }

    public function test_route_crud_akar_masalah_auditor_tersedia(): void
    {
        $routes = [
            'auditor.akarmasalah.index',
            'auditor.akarmasalah.create',
            'auditor.akarmasalah.store',
            'auditor.akarmasalah.show',
            'auditor.akarmasalah.edit',
            'auditor.akarmasalah.update',
            'auditor.akarmasalah.destroy',
        ];

        foreach ($routes as $route) {
            $this->assertTrue(
                Route::has($route),
                "Route {$route} tidak ditemukan."
            );
        }
    }
}