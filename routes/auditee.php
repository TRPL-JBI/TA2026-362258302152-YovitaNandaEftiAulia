<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardAuditeeController;
use App\Http\Controllers\AuditeePenerapanController;
use App\Http\Controllers\AuditeeTanggapanController;
use App\Http\Controllers\AuditeeStandarController;
use App\Http\Controllers\AuditeeMasterAuditController;

/*
|--------------------------------------------------------------------------
| ROUTE AUDITEE
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth.session',
    'auditee',
])
    ->prefix('auditee')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardAuditeeController::class, 'index']
        )->name('dashboard.auditee');

        /*
        |--------------------------------------------------------------------------
        | STANDAR MUTU
        |--------------------------------------------------------------------------
        |
        | Route utama untuk membuka detail standar mutu Auditee.
        |
        */

        Route::get(
            '/standar/{id}',
            [AuditeeStandarController::class, 'index']
        )->name('auditee.standar.index');

        /*
        |--------------------------------------------------------------------------
        | ALIAS URL LAMA
        |--------------------------------------------------------------------------
        |
        | URL /standar-mutu/{id} tetap dapat dipakai, tetapi diarahkan ke
        | route utama agar tidak ada nama route yang duplikat.
        |
        */

        Route::get(
            '/standar-mutu/{id}',
            function ($id) {
                return redirect()->route(
                    'auditee.standar.index',
                    $id
                );
            }
        )->name('auditee.standar.legacy');

        /*
        |--------------------------------------------------------------------------
        | PENERAPAN STANDAR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/penerapan/create/{standar}',
            [AuditeePenerapanController::class, 'create']
        )->name('auditee.penerapan.create');

        Route::post(
            '/penerapan',
            [AuditeePenerapanController::class, 'store']
        )->name('auditee.penerapan.store');

        Route::get(
            '/penerapan/{id}/edit',
            [AuditeePenerapanController::class, 'edit']
        )->name('auditee.penerapan.edit');

        Route::put(
            '/penerapan/{id}',
            [AuditeePenerapanController::class, 'update']
        )->name('auditee.penerapan.update');

        Route::delete(
            '/penerapan/{id}',
            [AuditeePenerapanController::class, 'destroy']
        )->name('auditee.penerapan.destroy');

        /*
        |--------------------------------------------------------------------------
        | TEMUAN DAN TANGGAPAN AUDITEE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/temuan',
            [AuditeeTanggapanController::class, 'index']
        )->name('auditee.temuan.index');

        Route::get(
            '/temuan/{id}',
            [AuditeeTanggapanController::class, 'show']
        )->name('auditee.temuan.show');

        Route::get(
            '/temuan/{id}/tanggapan',
            [AuditeeTanggapanController::class, 'create']
        )->name('auditee.tanggapan.create');

        Route::post(
            '/temuan/{id}/tanggapan',
            [AuditeeTanggapanController::class, 'store']
        )->name('auditee.tanggapan.store');

        Route::get(
            '/tanggapan/{id}/edit',
            [AuditeeTanggapanController::class, 'edit']
        )->name('auditee.tanggapan.edit');

        Route::put(
            '/tanggapan/{id}',
            [AuditeeTanggapanController::class, 'update']
        )->name('auditee.tanggapan.update');

        Route::delete(
            '/tanggapan/{id}',
            [AuditeeTanggapanController::class, 'destroy']
        )->name('auditee.tanggapan.destroy');

        /*
        |--------------------------------------------------------------------------
        | MASTER AUDIT AMI AUDITEE — VIEW ONLY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/audit-ami/temuan',
            [AuditeeMasterAuditController::class, 'temuanIndex']
        )->name('auditee.audit.temuan.index');

        Route::get(
            '/audit-ami/temuan/{id}',
            [AuditeeMasterAuditController::class, 'temuanShow']
        )->name('auditee.audit.temuan.show');

        Route::get(
            '/audit-ami/tim',
            [AuditeeMasterAuditController::class, 'timIndex']
        )->name('auditee.audit.tim.index');

        Route::get(
            '/audit-ami/tim/{id}',
            [AuditeeMasterAuditController::class, 'timShow']
        )->name('auditee.audit.tim.show');

        Route::get(
            '/audit-ami/jadwal',
            [AuditeeMasterAuditController::class, 'jadwalIndex']
        )->name('auditee.audit.jadwal.index');

        Route::get(
            '/audit-ami/jadwal/{id}',
            [AuditeeMasterAuditController::class, 'jadwalShow']
        )->name('auditee.audit.jadwal.show');
    });