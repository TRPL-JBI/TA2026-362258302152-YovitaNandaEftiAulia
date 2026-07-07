<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
  ->withMiddleware(function (Middleware $middleware) {

    $middleware->alias([

        'auth.session' => \App\Http\Middleware\CheckSession::class,

        'admin' => \App\Http\Middleware\AdminOnly::class,

        'auditor' => \App\Http\Middleware\AuditorOnly::class,

        'auditee' => \App\Http\Middleware\AuditeeOnly::class,

    ]);

})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
