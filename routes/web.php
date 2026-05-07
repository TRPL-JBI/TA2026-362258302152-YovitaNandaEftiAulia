<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\StandarMutuController;
use App\Http\Controllers\IsiStandarMutuController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth.session')->group(function () {

    // redirect awal
    Route::get('/', function () {
        return redirect()->route('unit-kerja.index');
    });

    /*
    |--------------------------------------------------------------------------
    | UNIT KERJA
    |--------------------------------------------------------------------------
    */
    Route::resource('unit-kerja', UnitKerjaController::class);




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
    Route::prefix('isi')->group(function () {

    Route::get('kategori/{standar}', [IsiStandarMutuController::class, 'kategori'])->name('isi.kategori');

    Route::get('jenis/{id}', [IsiStandarMutuController::class, 'jenis'])->name('isi.jenis');

    Route::get('sub/{id}', [IsiStandarMutuController::class, 'sub'])->name('isi.sub');

    Route::post('store', [IsiStandarMutuController::class, 'store'])->name('isi.store');

    Route::get('detail/{id}', [IsiStandarMutuController::class, 'show'])->name('isi.show');

    Route::get('edit/{id}', [IsiStandarMutuController::class, 'edit'])->name('isi.edit');

    Route::put('update/{id}', [IsiStandarMutuController::class, 'update'])->name('isi.update');

    Route::delete('{id}', [IsiStandarMutuController::class, 'destroy'])->name('isi.destroy');

});

 /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    Route::resource('user', UserController::class);

});