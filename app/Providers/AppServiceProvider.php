<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Services\StandarTreeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(

            [
                'layouts.app',
                'layouts.auditor',
                'layouts.auditee'
            ],

            function ($view) {

                $tree = app(
                    StandarTreeService::class
                )->getTree();

                $view->with(
                    'sidebarStandar',
                    $tree
                );

            }

        );
    }
}