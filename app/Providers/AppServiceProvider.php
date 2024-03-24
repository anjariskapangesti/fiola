<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('can_dept', function ($expression) {
            return "<?php if (auth()->check() && auth()->user()->hasDepartment($expression)): ?>";
        });

        Blade::directive('endcan_dept', function () {
            return "<?php endif; ?>";
        });
    }
}
