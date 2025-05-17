<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(Firebase::class, function ($app) {
            return new Firebase();
        });
    }

    public function boot() {}
}
