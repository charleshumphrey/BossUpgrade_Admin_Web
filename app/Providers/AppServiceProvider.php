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

    public function boot()
    {
        $path = env('GOOGLE_APPLICATION_CREDENTIALS');

        Log::info('🔥 Firebase Credentials Debug', [
            'path' => $path,
            'file_exists' => file_exists($path),
            'is_readable' => is_readable($path),
            'can_open' => is_file($path) && fopen($path, 'r') !== false,
            'content_snippet' => file_exists($path) ? substr(file_get_contents($path), 0, 100) : 'File missing',
        ]);
    }
}
