<?php

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\AuditeeOnly;
use App\Http\Middleware\AuditorOnly;
use App\Http\Middleware\CheckSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)
    /*
    |--------------------------------------------------------------------------
    | ROUTING
    |--------------------------------------------------------------------------
    */

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | MIDDLEWARE
    |--------------------------------------------------------------------------
    |
    | Alias middleware digunakan pada file route:
    |
    | check.session
    | admin
    | auditor
    | auditee
    |
    */

    ->withMiddleware(
        function (Middleware $middleware): void {
            $middleware->alias([
                'check.session' =>
                    CheckSession::class,

                'admin' =>
                    AdminOnly::class,

                'auditor' =>
                    AuditorOnly::class,

                'auditee' =>
                    AuditeeOnly::class,
            ]);
        }
    )

    /*
    |--------------------------------------------------------------------------
    | EXCEPTION
    |--------------------------------------------------------------------------
    */

    ->withExceptions(
        function (Exceptions $exceptions): void {
            //
        }
    )

    ->create();