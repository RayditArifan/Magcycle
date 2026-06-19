<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $appUrl = config('app.url');
        if ($appUrl && !str_contains($appUrl, 'localhost') && !str_contains($appUrl, '127.0.0.1')) {
            URL::forceRootUrl($appUrl);
        }
    }
}
