<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\StandarMutuController;
use App\Http\Controllers\IsiStandarMutuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeriodeAmiController;
use App\Http\Controllers\PertanyaanAmiController;
use App\Http\Controllers\PenerapanStandarController;
use App\Http\Controllers\TimAmiController;
use App\Http\Controllers\IndikatorStandarController;
use App\Http\Controllers\JadwalAmiController;

Route::middleware([
    'auth.session',
    'admin'
])->group(function(){

    // semua CRUD Admin
    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | STANDAR MUTU
    |--------------------------------------------------------------------------
    */

    Route::resource('standarmutu', StandarMutuController::class);

    /*
|--------------------------------------------------------------------------
| ISI STANDAR MUTU
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get(
    '/standarmutu/{standar}/isi',
    [IsiStandarMutuController::class,'index']
)->name('isi.index');

Route::get(
    '/standarmutu/{standar}/isi/create',
    [IsiStandarMutuController::class,'create']
)->name('isi.create');

Route::post(
    '/standarmutu/{standar}/isi',
    [IsiStandarMutuController::class,'store']
)->name('isi.store');


/*
|--------------------------------------------------------------------------
| NODE
|--------------------------------------------------------------------------
*/

Route::get(
    '/isi/{id}',
    [IsiStandarMutuController::class,'show']
)->name('isi.show');

Route::get(
    '/isi/{id}/detail',
    [IsiStandarMutuController::class,'detail']
)->name('isi.detail');

Route::get(
    '/isi/{id}/create',
    [IsiStandarMutuController::class,'create']
)->name('isi.node.create');

Route::post(
    '/isi/{id}',
    [IsiStandarMutuController::class,'store']
)->name('isi.node.store');

Route::get(
    '/isi/{id}/edit',
    [IsiStandarMutuController::class,'edit']
)->name('isi.edit');

Route::put(
    '/isi/{id}',
    [IsiStandarMutuController::class,'update']
)->name('isi.update');

Route::delete(
    '/isi/{id}',
    [IsiStandarMutuController::class,'destroy']
)->name('isi.destroy');

    /*
    |--------------------------------------------------------------------------
    | INDIKATOR
    |--------------------------------------------------------------------------
    */

    Route::get('/isi/{isi}/indikator', [IndikatorStandarController::class,'index'])->name('indikator.index');
    Route::get('/isi/{isi}/indikator/create', [IndikatorStandarController::class,'create'])->name('indikator.create');
    Route::post('/isi/{isi}/indikator/store', [IndikatorStandarController::class,'store'])->name('indikator.store');

    Route::get('/indikator/{indikator}', [IndikatorStandarController::class,'show'])->name('indikator.show');
    Route::get('/indikator/{indikator}/edit', [IndikatorStandarController::class,'edit'])->name('indikator.edit');
    Route::put('/indikator/{indikator}', [IndikatorStandarController::class,'update'])->name('indikator.update');
    Route::delete('/indikator/{indikator}', [IndikatorStandarController::class,'destroy'])->name('indikator.destroy');

    /*
    |--------------------------------------------------------------------------
    | UNIT KERJA
    |--------------------------------------------------------------------------
    */

    Route::resource('unit-kerja', UnitKerjaController::class);

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    Route::resource('user', UserController::class);

    /*
    |--------------------------------------------------------------------------
    | PERIODE AMI
    |--------------------------------------------------------------------------
    */

    Route::get('/periode-ami/delete/{id}', [PeriodeAmiController::class,'delete'])
        ->name('periode-ami.delete');

    Route::resource('periode-ami', PeriodeAmiController::class);

    /*
    |--------------------------------------------------------------------------
    | PERTANYAAN AMI
    |--------------------------------------------------------------------------
    */

    Route::get('/periode-ami/{id}/pertanyaan', [PertanyaanAmiController::class,'index'])->name('pertanyaan.index');
    Route::get('/periode-ami/{id}/pertanyaan/create', [PertanyaanAmiController::class,'create'])->name('pertanyaan.create');
    Route::post('/periode-ami/pertanyaan/store', [PertanyaanAmiController::class,'store'])->name('pertanyaan.store');

    Route::get('/pertanyaan/{id}/edit', [PertanyaanAmiController::class,'edit'])->name('pertanyaan.edit');
    Route::put('/pertanyaan/{id}', [PertanyaanAmiController::class,'update'])->name('pertanyaan.update');
    Route::delete('/pertanyaan/{id}', [PertanyaanAmiController::class,'destroy'])->name('pertanyaan.destroy');

    /*
    |--------------------------------------------------------------------------
    | PENERAPAN STANDAR
    |--------------------------------------------------------------------------
    */

    Route::get('/periode-ami/{id}/penerapan-standar', [PenerapanStandarController::class,'index'])->name('penerapan.index');

    Route::get('/periode-ami/{id}/penerapan-standar/{penerapan}', [PenerapanStandarController::class,'show'])->name('penerapan.show');

    /*
    |--------------------------------------------------------------------------
    | TIM AMI
    |--------------------------------------------------------------------------
    */

    Route::get('/periode-ami/{periode}/tim-ami', [TimAmiController::class,'index'])->name('tim-ami.index');
    Route::get('/periode-ami/{periode}/tim-ami/create', [TimAmiController::class,'create'])->name('tim-ami.create');
    Route::post('/periode-ami/{periode}/tim-ami', [TimAmiController::class,'store'])->name('tim-ami.store');

    Route::get('/tim-ami/{id}', [TimAmiController::class,'show'])->name('tim-ami.show');
    Route::get('/tim-ami/{id}/edit', [TimAmiController::class,'edit'])->name('tim-ami.edit');
    Route::put('/tim-ami/{id}', [TimAmiController::class,'update'])->name('tim-ami.update');
    Route::delete('/tim-ami/{id}', [TimAmiController::class,'destroy'])->name('tim-ami.destroy');

    /*
    |--------------------------------------------------------------------------
    | JADWAL AMI
    |--------------------------------------------------------------------------
    */

    Route::get('/periode-ami/{periode}/jadwal', [JadwalAmiController::class,'index'])->name('jadwal.index');
    Route::get('/periode-ami/{periode}/jadwal/create', [JadwalAmiController::class,'create'])->name('jadwal.create');
    Route::post('/periode-ami/{periode}/jadwal', [JadwalAmiController::class,'store'])->name('jadwal.store');

    Route::get('/jadwal/{id}', [JadwalAmiController::class,'show'])->name('jadwal.show');
    Route::get('/jadwal/{id}/edit', [JadwalAmiController::class,'edit'])->name('jadwal.edit');
    Route::get('/jadwal/delete/{id}', [JadwalAmiController::class,'delete'])->name('jadwal.delete');

    Route::put('/jadwal/{id}', [JadwalAmiController::class,'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalAmiController::class,'destroy'])->name('jadwal.destroy');

});