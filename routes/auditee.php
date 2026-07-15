<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
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
    'auditee'
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
    [DashboardController::class,'dashboardAuditee']
)->name('dashboard.auditee');

/*
|--------------------------------------------------------------------------
| STANDAR MUTU
|--------------------------------------------------------------------------
*/

Route::get(
    '/standar/{id}',
    [AuditeeStandarController::class,'index']
)->name('auditee.standar.index');

/*
|--------------------------------------------------------------------------
| PENERAPAN STANDAR
|--------------------------------------------------------------------------
*/
Route::get(
            '/standar-mutu/{id}',
            [AuditeeStandarController::class, 'index']
        )->name('auditee.standar.index');

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
| TANGGAPAN AUDITEE
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
| MASTER AUDIT AMI UNTUK AUDITEE - VIEW ONLY
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