<?php

namespace App\Providers;

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
    // Хэрэв Production (Vercel) дээр байвал HTTPS-ийг албадна
    if($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}
}
