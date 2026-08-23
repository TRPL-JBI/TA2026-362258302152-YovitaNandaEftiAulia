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

    /*
    |--------------------------------------------------------------------------
    | 1. STRUKTUR TABEL
    |--------------------------------------------------------------------------
    */

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
                    'link_file',
                    'id_user',
                ]
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 2. MODEL MENGGUNAKAN TABEL YANG BENAR
    |--------------------------------------------------------------------------
    */

    public function test_model_lampiran_audit_menggunakan_tabel_yang_benar(): void
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
            'link_file',
            $model->getFillable()
        );

        $this->assertContains(
            'id_user',
            $model->getFillable()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3. ROUTE CRUD LAMPIRAN TERSEDIA
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | 4. UPDATE TIDAK BOLEH MEMINDAHKAN PERIODE
    |--------------------------------------------------------------------------
    |
    | Poin revisi:
    |
    | Lampiran yang sudah menjadi bukti satu periode
    | tidak boleh dipindahkan ke periode lain.
    |
    | Controller sekarang hanya meng-update link_file.
    |
    */

    public function test_id_periode_ami_tidak_boleh_diubah_saat_update(): void
    {
        $lampiran = new LampiranAudit();

        /*
        |--------------------------------------------------------------------------
        | Pastikan field periode tetap tersedia pada model.
        |--------------------------------------------------------------------------
        */

        $fillable = $lampiran->getFillable();

        $this->assertContains(
            'id_periode_ami',
            $fillable
        );

        /*
        |--------------------------------------------------------------------------
        | Ambil method update dari controller.
        |--------------------------------------------------------------------------
        |
        | Pemeriksaan dilakukan melalui source controller.
        | Kita memastikan update tidak lagi memasukkan:
        |
        | 'id_periode_ami' => ...
        |
        | ke dalam update().
        |--------------------------------------------------------------------------
        */

        $controllerPath =
            app_path(
                'Http/Controllers/LampiranAuditorController.php'
            );

        $this->assertFileExists(
            $controllerPath
        );

        $source = file_get_contents(
            $controllerPath
        );

        $this->assertNotFalse(
            $source
        );

        /*
        |--------------------------------------------------------------------------
        | Cari bagian method update()
        |--------------------------------------------------------------------------
        */

        $posUpdate = strpos(
            $source,
            'public function update('
        );

        $this->assertNotFalse(
            $posUpdate,
            'Method update() tidak ditemukan.'
        );

        /*
        |--------------------------------------------------------------------------
        | Ambil bagian setelah update()
        |--------------------------------------------------------------------------
        */

        $bagianUpdate = substr(
            $source,
            $posUpdate
        );

        /*
        |--------------------------------------------------------------------------
        | Batasi sampai sebelum destroy()
        |--------------------------------------------------------------------------
        */

        $posDestroy = strpos(
            $bagianUpdate,
            'public function destroy('
        );

        if ($posDestroy !== false) {
            $bagianUpdate = substr(
                $bagianUpdate,
                0,
                $posDestroy
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE TIDAK BOLEH MENGUBAH id_periode_ami
        |--------------------------------------------------------------------------
        */

        $this->assertStringNotContainsString(
            "'id_periode_ami' =>",
            $bagianUpdate,
            'Method update() masih mencoba mengubah id_periode_ami.'
        );

        $this->assertStringNotContainsString(
            '"id_periode_ami" =>',
            $bagianUpdate,
            'Method update() masih mencoba mengubah id_periode_ami.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 5. UPDATE MASIH BOLEH MENGUBAH LINK FILE
    |--------------------------------------------------------------------------
    */

    public function test_update_masih_mengizinkan_perubahan_link_file(): void
    {
        $controllerPath =
            app_path(
                'Http/Controllers/LampiranAuditorController.php'
            );

        $this->assertFileExists(
            $controllerPath
        );

        $source = file_get_contents(
            $controllerPath
        );

        $this->assertNotFalse(
            $source
        );

        $posUpdate = strpos(
            $source,
            'public function update('
        );

        $this->assertNotFalse(
            $posUpdate,
            'Method update() tidak ditemukan.'
        );

        $bagianUpdate = substr(
            $source,
            $posUpdate
        );

        $posDestroy = strpos(
            $bagianUpdate,
            'public function destroy('
        );

        if ($posDestroy !== false) {
            $bagianUpdate = substr(
                $bagianUpdate,
                0,
                $posDestroy
            );
        }

        $this->assertStringContainsString(
            'link_file',
            $bagianUpdate,
            'Method update() tidak lagi menangani link_file.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 6. UPDATE MEMERIKSA PERIODE ASAL
    |--------------------------------------------------------------------------
    */

    public function test_update_memeriksa_periode_lampiran_asli(): void
    {
        $controllerPath =
            app_path(
                'Http/Controllers/LampiranAuditorController.php'
            );

        $source = file_get_contents(
            $controllerPath
        );

        $this->assertNotFalse(
            $source
        );

        $posUpdate = strpos(
            $source,
            'public function update('
        );

        $this->assertNotFalse(
            $posUpdate
        );

        $bagianUpdate = substr(
            $source,
            $posUpdate
        );

        $posDestroy = strpos(
            $bagianUpdate,
            'public function destroy('
        );

        if ($posDestroy !== false) {
            $bagianUpdate = substr(
                $bagianUpdate,
                0,
                $posDestroy
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Lampiran harus ditemukan terlebih dahulu
        |--------------------------------------------------------------------------
        */

        $this->assertStringContainsString(
            'findLampiranAuditor',
            $bagianUpdate
        );

        /*
        |--------------------------------------------------------------------------
        | Periode asli harus diperiksa
        |--------------------------------------------------------------------------
        */

        $this->assertStringContainsString(
            'ensurePeriodeAmiTerbuka',
            $bagianUpdate
        );

        $this->assertStringContainsString(
            'data->id_periode_ami',
            $bagianUpdate
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 7. UPDATE TIDAK MEMINDAHKAN PERIODE MELALUI DATA VALIDASI
    |--------------------------------------------------------------------------
    */

    public function test_update_tidak_memvalidasi_id_periode_sebagai_data_update(): void
    {
        $controllerPath =
            app_path(
                'Http/Controllers/LampiranAuditorController.php'
            );

        $source = file_get_contents(
            $controllerPath
        );

        $this->assertNotFalse(
            $source
        );

        $posUpdate = strpos(
            $source,
            'public function update('
        );

        $this->assertNotFalse(
            $posUpdate
        );

        $bagianUpdate = substr(
            $source,
            $posUpdate
        );

        $posDestroy = strpos(
            $bagianUpdate,
            'public function destroy('
        );

        if ($posDestroy !== false) {
            $bagianUpdate = substr(
                $bagianUpdate,
                0,
                $posDestroy
            );
        }

        /*
        |--------------------------------------------------------------------------
        | id_periode_ami tidak boleh masuk ke array update().
        |--------------------------------------------------------------------------
        */

        $this->assertDoesNotMatchRegularExpression(
            "/->update\s*\(\s*\[[^\]]*['\"]id_periode_ami['\"]\s*=>/s",
            $bagianUpdate,
            'id_periode_ami masih dapat diubah melalui update().'
        );
    }
}