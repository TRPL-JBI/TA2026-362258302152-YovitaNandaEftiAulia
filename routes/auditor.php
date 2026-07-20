<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardAuditorController;
use App\Http\Controllers\StandarMutuAuditorController;
use App\Http\Controllers\IsiStandarAuditorController;
use App\Http\Controllers\IndikatorAuditorController;
use App\Http\Controllers\PeriodeAuditorController;
use App\Http\Controllers\JadwalAuditorController;
use App\Http\Controllers\TimAuditorController;
use App\Http\Controllers\PenerapanAuditorController;
use App\Http\Controllers\TemuanAuditorController;
use App\Http\Controllers\TanggapanAuditorController;
use App\Http\Controllers\AkarMasalahAuditorController;
use App\Http\Controllers\RekomendasiAuditorController;
use App\Http\Controllers\KesimpulanAuditorController;
use App\Http\Controllers\LampiranAuditorController;
use App\Http\Controllers\LaporanAuditorController;

/*
|--------------------------------------------------------------------------
| ROUTE AUDITOR
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth.session',
    'auditor',
])
    ->prefix('auditor')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardAuditorController::class, 'index']
        )->name('dashboard.auditor');

        /*
        |--------------------------------------------------------------------------
        | STANDAR MUTU
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/standar-mutu',
            [StandarMutuAuditorController::class, 'index']
        )->name('auditor.standarmutu.index');

        Route::get(
            '/standar-mutu/{id}',
            [StandarMutuAuditorController::class, 'show']
        )->name('auditor.standarmutu.show');

        /*
        |--------------------------------------------------------------------------
        | ISI STANDAR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/standar-mutu/{id}/isi',
            [IsiStandarAuditorController::class, 'index']
        )->name('auditor.isi.index');

        Route::get(
            '/isi/{id}',
            [IsiStandarAuditorController::class, 'show']
        )->name('auditor.isi.show');

        Route::get(
            '/isi/{id}/detail',
            [IsiStandarAuditorController::class, 'detail']
        )->name('auditor.isi.detail');

        /*
        |--------------------------------------------------------------------------
        | INDIKATOR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/isi/{id}/indikator',
            [IndikatorAuditorController::class, 'index']
        )->name('auditor.indikator.index');

        Route::get(
            '/indikator/{id}',
            [IndikatorAuditorController::class, 'show']
        )->name('auditor.indikator.show');

        /*
        |--------------------------------------------------------------------------
        | PERIODE AMI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periode',
            [PeriodeAuditorController::class, 'index']
        )->name('auditor.periode.index');

        Route::get(
            '/periode/{id}',
            [PeriodeAuditorController::class, 'show']
        )->name('auditor.periode.show');

        /*
        |--------------------------------------------------------------------------
        | JADWAL AMI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periode/{id}/jadwal',
            [JadwalAuditorController::class, 'index']
        )->name('auditor.jadwal.index');

        Route::get(
            '/jadwal/{id}',
            [JadwalAuditorController::class, 'show']
        )->name('auditor.jadwal.show');

        /*
        |--------------------------------------------------------------------------
        | TIM AMI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periode/{id}/tim',
            [TimAuditorController::class, 'index']
        )->name('auditor.tim.index');

        Route::get(
            '/tim/{id}',
            [TimAuditorController::class, 'show']
        )->name('auditor.tim.show');

        /*
        |--------------------------------------------------------------------------
        | PENERAPAN STANDAR
        |--------------------------------------------------------------------------
        |
        | Auditor hanya melihat penerapan Auditee.
        | Auditor tidak boleh menambah, mengubah, atau menghapus penerapan.
        |
        */

        Route::get(
            '/periode/{id}/penerapan',
            [PenerapanAuditorController::class, 'index']
        )->name('auditor.penerapan.index');

        Route::get(
            '/periode/{id}/penerapan/{penerapan}',
            [PenerapanAuditorController::class, 'show']
        )->name('auditor.penerapan.show');

        /*
        |--------------------------------------------------------------------------
        | TEMUAN AUDIT
        |--------------------------------------------------------------------------
        |
        | Halaman index menampilkan struktur yang sama dengan Penerapan Standar.
        |
        | Temuan hanya dapat dibuat berdasarkan Penerapan Standar yang:
        | 1. Sudah mempunyai deskripsi hasil.
        | 2. Sudah mempunyai link bukti.
        |
        */

        Route::get(
            '/temuan',
            [TemuanAuditorController::class, 'index']
        )->name('auditor.temuan.index');

        Route::get(
            '/temuan/penerapan/{penerapan}/create',
            [TemuanAuditorController::class, 'create']
        )->name('auditor.temuan.create');

        Route::post(
            '/temuan',
            [TemuanAuditorController::class, 'store']
        )->name('auditor.temuan.store');

        Route::get(
            '/temuan/{temuan}',
            [TemuanAuditorController::class, 'show']
        )->name('auditor.temuan.show');

        Route::get(
            '/temuan/{temuan}/edit',
            [TemuanAuditorController::class, 'edit']
        )->name('auditor.temuan.edit');

        Route::put(
            '/temuan/{temuan}',
            [TemuanAuditorController::class, 'update']
        )->name('auditor.temuan.update');

        Route::delete(
            '/temuan/{temuan}',
            [TemuanAuditorController::class, 'destroy']
        )->name('auditor.temuan.destroy');

        /*
        |--------------------------------------------------------------------------
        | TANGGAPAN AUDITEE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/tanggapan',
            [TanggapanAuditorController::class, 'index']
        )->name('auditor.tanggapan.index');

        Route::get(
            '/tanggapan/{id}',
            [TanggapanAuditorController::class, 'show']
        )->name('auditor.tanggapan.show');

        /*
        |--------------------------------------------------------------------------
        | AKAR MASALAH
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/akar-masalah',
            [AkarMasalahAuditorController::class, 'index']
        )->name('auditor.akarmasalah.index');

        Route::get(
            '/akar-masalah/create',
            [AkarMasalahAuditorController::class, 'create']
        )->name('auditor.akarmasalah.create');

        Route::post(
            '/akar-masalah',
            [AkarMasalahAuditorController::class, 'store']
        )->name('auditor.akarmasalah.store');

        Route::get(
            '/akar-masalah/{id}',
            [AkarMasalahAuditorController::class, 'show']
        )->name('auditor.akarmasalah.show');

        Route::get(
            '/akar-masalah/{id}/edit',
            [AkarMasalahAuditorController::class, 'edit']
        )->name('auditor.akarmasalah.edit');

        Route::put(
            '/akar-masalah/{id}',
            [AkarMasalahAuditorController::class, 'update']
        )->name('auditor.akarmasalah.update');

        Route::delete(
            '/akar-masalah/{id}',
            [AkarMasalahAuditorController::class, 'destroy']
        )->name('auditor.akarmasalah.destroy');

        /*
        |--------------------------------------------------------------------------
        | REKOMENDASI PENINGKATAN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/rekomendasi',
            [RekomendasiAuditorController::class, 'index']
        )->name('auditor.rekomendasi.index');

        Route::get(
            '/rekomendasi/create',
            [RekomendasiAuditorController::class, 'create']
        )->name('auditor.rekomendasi.create');

        Route::post(
            '/rekomendasi',
            [RekomendasiAuditorController::class, 'store']
        )->name('auditor.rekomendasi.store');

        Route::get(
            '/rekomendasi/{id}',
            [RekomendasiAuditorController::class, 'show']
        )->name('auditor.rekomendasi.show');

        Route::get(
            '/rekomendasi/{id}/edit',
            [RekomendasiAuditorController::class, 'edit']
        )->name('auditor.rekomendasi.edit');

        Route::put(
            '/rekomendasi/{id}',
            [RekomendasiAuditorController::class, 'update']
        )->name('auditor.rekomendasi.update');

        Route::delete(
            '/rekomendasi/{id}',
            [RekomendasiAuditorController::class, 'destroy']
        )->name('auditor.rekomendasi.destroy');

        /*
        |--------------------------------------------------------------------------
        | KESIMPULAN AUDIT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/kesimpulan',
            [KesimpulanAuditorController::class, 'index']
        )->name('auditor.kesimpulan.index');

        Route::get(
            '/kesimpulan/create',
            [KesimpulanAuditorController::class, 'create']
        )->name('auditor.kesimpulan.create');

        Route::post(
            '/kesimpulan',
            [KesimpulanAuditorController::class, 'store']
        )->name('auditor.kesimpulan.store');

        Route::get(
            '/kesimpulan/{id}',
            [KesimpulanAuditorController::class, 'show']
        )->name('auditor.kesimpulan.show');

        Route::get(
            '/kesimpulan/{id}/edit',
            [KesimpulanAuditorController::class, 'edit']
        )->name('auditor.kesimpulan.edit');

        Route::put(
            '/kesimpulan/{id}',
            [KesimpulanAuditorController::class, 'update']
        )->name('auditor.kesimpulan.update');

        Route::delete(
            '/kesimpulan/{id}',
            [KesimpulanAuditorController::class, 'destroy']
        )->name('auditor.kesimpulan.destroy');

        /*
        |--------------------------------------------------------------------------
        | LAMPIRAN AUDIT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/lampiran',
            [LampiranAuditorController::class, 'index']
        )->name('auditor.lampiran.index');

        Route::get(
            '/lampiran/create',
            [LampiranAuditorController::class, 'create']
        )->name('auditor.lampiran.create');

        Route::post(
            '/lampiran',
            [LampiranAuditorController::class, 'store']
        )->name('auditor.lampiran.store');

        Route::get(
            '/lampiran/{id}',
            [LampiranAuditorController::class, 'show']
        )->name('auditor.lampiran.show');

        Route::get(
            '/lampiran/{id}/edit',
            [LampiranAuditorController::class, 'edit']
        )->name('auditor.lampiran.edit');

        Route::put(
            '/lampiran/{id}',
            [LampiranAuditorController::class, 'update']
        )->name('auditor.lampiran.update');

        Route::delete(
            '/lampiran/{id}',
            [LampiranAuditorController::class, 'destroy']
        )->name('auditor.lampiran.destroy');

        /*
        |--------------------------------------------------------------------------
        | LAPORAN AMI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/laporan',
            [LaporanAuditorController::class, 'index']
        )->name('auditor.laporan.index');

        Route::get(
            '/laporan/{id}',
            [LaporanAuditorController::class, 'show']
        )->name('auditor.laporan.show');
    });