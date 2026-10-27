<?php

namespace App\Providers;

use App\Models\Database\Configuration;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Gate::before(function ($user, $ability) {
            return $user->hasRole('God Admin') ? true : null;
        });

        if ($this->app->runningInConsole()) {
            return;
        }

        try {
            $configuration = Schema::hasTable('configurations')
                ? Configuration::first()
                : null;

            View::share('configuration', $configuration);
        } catch (\Throwable $e) {
            // Cegah error jika database belum siap / migrasi
        }
    }
}
