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
| AUTH
|--------------------------------------------------------------------------
*/

// Login
Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login.process');

// Logout
Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');

/*
|--------------------------------------------------------------------------
| LOAD ROUTES BERDASARKAN ROLE
|--------------------------------------------------------------------------
*/

require __DIR__.'/admin.php';

require __DIR__.'/auditee.php';

require __DIR__.'/auditor.php';