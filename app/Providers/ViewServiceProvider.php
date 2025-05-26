<?php

namespace App\Providers;

use App\Models\option;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
         View::composer('*', function ($view) {
            // Grab all options as name => value pairs
            $globalOptions = option::pluck('value', 'type')->toArray();
            $view->with('globalOptions', $globalOptions);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
