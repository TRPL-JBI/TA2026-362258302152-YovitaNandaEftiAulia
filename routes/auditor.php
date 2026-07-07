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
use App\Http\Controllers\PertanyaanAuditorController;
use App\Http\Controllers\TemuanAuditorController;

/*
|--------------------------------------------------------------------------
| ROUTE AUDITOR
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth.session',
    'auditor'
])->prefix('auditor')->group(function () {

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
    | PERTANYAAN AMI
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/periode/{id}/pertanyaan',
        [PertanyaanAuditorController::class, 'index']
    )->name('auditor.pertanyaan.index');

    Route::get(
        '/periode/{id}/pertanyaan/create',
        [PertanyaanAuditorController::class, 'create']
    )->name('auditor.pertanyaan.create');

    Route::post(
        '/periode/{id}/pertanyaan',
        [PertanyaanAuditorController::class, 'store']
    )->name('auditor.pertanyaan.store');

    Route::get(
        '/pertanyaan/{pertanyaan}',
        [PertanyaanAuditorController::class, 'show']
    )->name('auditor.pertanyaan.show');

    Route::get(
        '/pertanyaan/{pertanyaan}/edit',
        [PertanyaanAuditorController::class, 'edit']
    )->name('auditor.pertanyaan.edit');

    Route::put(
        '/pertanyaan/{pertanyaan}',
        [PertanyaanAuditorController::class, 'update']
    )->name('auditor.pertanyaan.update');

    Route::delete(
        '/pertanyaan/{pertanyaan}',
        [PertanyaanAuditorController::class, 'destroy']
    )->name('auditor.pertanyaan.destroy');

    /*
    |--------------------------------------------------------------------------
    | TEMUAN AUDIT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/temuan',
        [TemuanAuditorController::class, 'index']
    )->name('auditor.temuan.index');

    Route::get(
        '/temuan/create',
        [TemuanAuditorController::class, 'create']
    )->name('auditor.temuan.create');

    Route::post(
        '/temuan',
        [TemuanAuditorController::class, 'store']
    )->name('auditor.temuan.store');

    Route::get(
        '/temuan/{id}',
        [TemuanAuditorController::class, 'show']
    )->name('auditor.temuan.show');

    Route::get(
        '/temuan/{id}/edit',
        [TemuanAuditorController::class, 'edit']
    )->name('auditor.temuan.edit');

    Route::put(
        '/temuan/{id}',
        [TemuanAuditorController::class, 'update']
    )->name('auditor.temuan.update');

    Route::delete(
        '/temuan/{id}',
        [TemuanAuditorController::class, 'destroy']
    )->name('auditor.temuan.destroy');

});