<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- ЭНЭ МӨР ДУТУУ БАЙНА! ЗААВАЛ НЭМ!

class AppServiceProvider extends ServiceProvider
{
    // ... бусад код ...
    
    public function boot(): void
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https'); // Энд алдаа зааж байсан
        }
    }
}