<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndikatorStandarController;
use App\Http\Controllers\IsiStandarMutuController;
use App\Http\Controllers\JadwalAmiController;
use App\Http\Controllers\LaporanAdminController;
use App\Http\Controllers\PenerapanStandarController;
use App\Http\Controllers\PeriodeAmiController;
use App\Http\Controllers\StandarMutuController;
use App\Http\Controllers\TimAmiController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserController;

Route::middleware([
    'check.session',
    'admin',
])
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard.admin');

        /*
        |--------------------------------------------------------------------------
        | STANDAR MUTU
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'standarmutu',
            StandarMutuController::class
        );

        /*
        |--------------------------------------------------------------------------
        | ISI STANDAR MUTU - ROOT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/standarmutu/{standar}/isi',
            [IsiStandarMutuController::class, 'index']
        )->name('isi.index');

        Route::get(
            '/standarmutu/{standar}/isi/create',
            [IsiStandarMutuController::class, 'create']
        )->name('isi.create');

        Route::post(
            '/standarmutu/{standar}/isi',
            [IsiStandarMutuController::class, 'store']
        )->name('isi.store');

        /*
        |--------------------------------------------------------------------------
        | ISI STANDAR MUTU - NODE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/isi/{id}',
            [IsiStandarMutuController::class, 'show']
        )->name('isi.show');

        Route::get(
            '/isi/{id}/detail',
            [IsiStandarMutuController::class, 'detail']
        )->name('isi.detail');

        Route::get(
            '/isi/{id}/create',
            [IsiStandarMutuController::class, 'create']
        )->name('isi.node.create');

        Route::post(
            '/isi/{id}',
            [IsiStandarMutuController::class, 'store']
        )->name('isi.node.store');

        Route::get(
            '/isi/{id}/edit',
            [IsiStandarMutuController::class, 'edit']
        )->name('isi.edit');

        Route::put(
            '/isi/{id}',
            [IsiStandarMutuController::class, 'update']
        )->name('isi.update');

        Route::delete(
            '/isi/{id}',
            [IsiStandarMutuController::class, 'destroy']
        )->name('isi.destroy');

        /*
        |--------------------------------------------------------------------------
        | INDIKATOR STANDAR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/isi/{isi}/indikator',
            [IndikatorStandarController::class, 'index']
        )->name('indikator.index');

        Route::get(
            '/isi/{isi}/indikator/create',
            [IndikatorStandarController::class, 'create']
        )->name('indikator.create');

        Route::post(
            '/isi/{isi}/indikator/store',
            [IndikatorStandarController::class, 'store']
        )->name('indikator.store');

        Route::get(
            '/indikator/{indikator}',
            [IndikatorStandarController::class, 'show']
        )->name('indikator.show');

        Route::get(
            '/indikator/{indikator}/edit',
            [IndikatorStandarController::class, 'edit']
        )->name('indikator.edit');

        Route::put(
            '/indikator/{indikator}',
            [IndikatorStandarController::class, 'update']
        )->name('indikator.update');

        Route::delete(
            '/indikator/{indikator}',
            [IndikatorStandarController::class, 'destroy']
        )->name('indikator.destroy');

        /*
        |--------------------------------------------------------------------------
        | UNIT KERJA
        |--------------------------------------------------------------------------
        |
        | Parameter resource dibuat menjadi {id} agar sesuai dengan:
        |
        | show(int $id)
        | edit(int $id)
        | update(Request $request, int $id)
        | destroy(int $id)
        |
        */

        Route::resource(
            'unit-kerja',
            UnitKerjaController::class
        )->parameters([
            'unit-kerja' => 'id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'user',
            UserController::class
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE AMI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periode-ami/delete/{id}',
            [PeriodeAmiController::class, 'delete']
        )->name('periode-ami.delete');

        Route::resource(
            'periode-ami',
            PeriodeAmiController::class
        );

        /*
        |--------------------------------------------------------------------------
        | PENERAPAN STANDAR - READ ONLY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periode-ami/{id}/penerapan-standar',
            [PenerapanStandarController::class, 'index']
        )->name('penerapan.index');

        Route::get(
            '/periode-ami/{id}/penerapan-standar/{penerapan}',
            [PenerapanStandarController::class, 'show']
        )->name('penerapan.show');

        /*
        |--------------------------------------------------------------------------
        | TIM AMI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periode-ami/{periode}/tim-ami',
            [TimAmiController::class, 'index']
        )->name('tim-ami.index');

        Route::get(
            '/periode-ami/{periode}/tim-ami/create',
            [TimAmiController::class, 'create']
        )->name('tim-ami.create');

        Route::post(
            '/periode-ami/{periode}/tim-ami',
            [TimAmiController::class, 'store']
        )->name('tim-ami.store');

        Route::get(
            '/tim-ami/{id}',
            [TimAmiController::class, 'show']
        )->name('tim-ami.show');

        Route::get(
            '/tim-ami/{id}/edit',
            [TimAmiController::class, 'edit']
        )->name('tim-ami.edit');

        Route::put(
            '/tim-ami/{id}',
            [TimAmiController::class, 'update']
        )->name('tim-ami.update');

        Route::delete(
            '/tim-ami/{id}',
            [TimAmiController::class, 'destroy']
        )->name('tim-ami.destroy');

        /*
        |--------------------------------------------------------------------------
        | JADWAL AMI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/periode-ami/{periode}/jadwal',
            [JadwalAmiController::class, 'index']
        )->name('jadwal.index');

        Route::get(
            '/periode-ami/{periode}/jadwal/create',
            [JadwalAmiController::class, 'create']
        )->name('jadwal.create');

        Route::post(
            '/periode-ami/{periode}/jadwal',
            [JadwalAmiController::class, 'store']
        )->name('jadwal.store');

        Route::get(
            '/jadwal/{id}',
            [JadwalAmiController::class, 'show']
        )->name('jadwal.show');

        Route::get(
            '/jadwal/{id}/edit',
            [JadwalAmiController::class, 'edit']
        )->name('jadwal.edit');

        Route::get(
            '/jadwal/delete/{id}',
            [JadwalAmiController::class, 'delete']
        )->name('jadwal.delete');

        Route::put(
            '/jadwal/{id}',
            [JadwalAmiController::class, 'update']
        )->name('jadwal.update');

        Route::delete(
            '/jadwal/{id}',
            [JadwalAmiController::class, 'destroy']
        )->name('jadwal.destroy');

        /*
        |--------------------------------------------------------------------------
        | LAPORAN AMI ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/laporan-ami',
            [LaporanAdminController::class, 'index']
        )->name('laporan.index');

        Route::get(
            '/laporan-ami/{id}/pdf',
            [LaporanAdminController::class, 'pdf']
        )->name('laporan.pdf');
    });