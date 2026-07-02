<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| AUDITEE
|--------------------------------------------------------------------------
*/

Route::middleware('auth.session')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD AUDITEE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard-auditee',
        [DashboardController::class, 'dashboardAuditee']
    )->name('dashboard.auditee');

});