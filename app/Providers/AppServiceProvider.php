<?php

namespace App\Providers;

use App\View\Components\Table;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
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
        Paginator::useBootstrapFive();
        // Blade::component('table', Table::class);
        Activity::saving(function (Activity $activity) {
            if (! $activity->causer_id && Auth::check()) {
                $activity->causer_id = Auth::id();
                $activity->causer_type = get_class(Auth::user());
            }
        });
    }
}
