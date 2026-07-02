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
| AUDITOR
|--------------------------------------------------------------------------
*/

Route::middleware('auth.session')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD AUDITOR
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard-auditor',
        [DashboardAuditorController::class,'index']
    )->name('dashboard.auditor');

/*
|--------------------------------------------------------------------------
| STANDAR MUTU
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/standar-mutu',
    [StandarMutuAuditorController::class,'index']
)->name('auditor.standarmutu.index');

Route::get(
    '/auditor/standar-mutu/{id}',
    [StandarMutuAuditorController::class,'show']
)->name('auditor.standarmutu.show');

/*
|--------------------------------------------------------------------------
| ISI STANDAR AUDITOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/standar-mutu/{id}/isi',
    [IsiStandarAuditorController::class,'index']
)->name('auditor.isi.index');

Route::get(
    '/auditor/isi/{id}',
    [IsiStandarAuditorController::class,'show']
)->name('auditor.isi.show');

/*
|--------------------------------------------------------------------------
| INDIKATOR AUDITOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/isi/{id}/indikator',
    [IndikatorAuditorController::class,'index']
)->name('auditor.indikator.index');

Route::get(
    '/auditor/indikator/{id}',
    [IndikatorAuditorController::class,'show']
)->name('auditor.indikator.show');

/*
|--------------------------------------------------------------------------
| PERIODE AMI AUDITOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/periode',
    [PeriodeAuditorController::class,'index']
)->name('auditor.periode.index');

Route::get(
    '/auditor/periode/{id}',
    [PeriodeAuditorController::class,'show']
)->name('auditor.periode.show');

/*
|--------------------------------------------------------------------------
| JADWAL AMI AUDITOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/periode/{id}/jadwal',
    [JadwalAuditorController::class,'index']
)->name('auditor.jadwal.index');

Route::get(
    '/auditor/jadwal/{id}',
    [JadwalAuditorController::class,'show']
)->name('auditor.jadwal.show');

/*
|--------------------------------------------------------------------------
| TIM AMI AUDITOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/periode/{id}/tim',
    [TimAuditorController::class,'index']
)->name('auditor.tim.index');

Route::get(
    '/auditor/tim/{id}',
    [TimAuditorController::class,'show']
)->name('auditor.tim.show');

/*
|--------------------------------------------------------------------------
| PENERAPAN STANDAR AUDITOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/periode/{id}/penerapan',
    [PenerapanAuditorController::class,'index']
)->name('auditor.penerapan.index');

Route::get(
    '/auditor/periode/{id}/penerapan/{penerapan}',
    [PenerapanAuditorController::class,'show']
)->name('auditor.penerapan.show');


/*
|--------------------------------------------------------------------------
| PERTANYAAN AMI AUDITOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/periode/{id}/pertanyaan',
    [PertanyaanAuditorController::class,'index']
)->name('auditor.pertanyaan.index');

Route::get(
    '/auditor/periode/{id}/pertanyaan/{pertanyaan}',
    [PertanyaanAuditorController::class,'show']
)->name('auditor.pertanyaan.show');

/*
|--------------------------------------------------------------------------
| TEMUAN AUDIT
|--------------------------------------------------------------------------
*/

Route::get(
    '/auditor/temuan',
    [TemuanAuditorController::class,'index']
)->name('auditor.temuan.index');

Route::get(
    '/auditor/temuan/create',
    [TemuanAuditorController::class,'create']
)->name('auditor.temuan.create');

Route::post(
    '/auditor/temuan',
    [TemuanAuditorController::class,'store']
)->name('auditor.temuan.store');

Route::get(
    '/auditor/temuan/{id}',
    [TemuanAuditorController::class,'show']
)->name('auditor.temuan.show');

Route::get(
    '/auditor/temuan/{id}/edit',
    [TemuanAuditorController::class,'edit']
)->name('auditor.temuan.edit');

Route::put(
    '/auditor/temuan/{id}',
    [TemuanAuditorController::class,'update']
)->name('auditor.temuan.update');

Route::delete(
    '/auditor/temuan/{id}',
    [TemuanAuditorController::class,'destroy']
)->name('auditor.temuan.destroy');

});