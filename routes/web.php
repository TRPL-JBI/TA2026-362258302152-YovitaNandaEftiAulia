<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')
    ->name('landing');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
|
| Aplikasi menggunakan session('user_id') sebagai autentikasi manual.
| Oleh karena itu, route login tidak menggunakan middleware guest
| bawaan Laravel.
|
*/

Route::get(
    '/login',
    [AuthController::class, 'index']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login.process');

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');

/*
|--------------------------------------------------------------------------
| LOAD ROUTES BERDASARKAN ROLE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/admin.php';

require __DIR__ . '/auditee.php';

require __DIR__ . '/auditor.php';