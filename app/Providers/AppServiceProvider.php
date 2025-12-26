<?php

namespace App\Providers;

use App\Models\Category;
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
        View::composer('layouts.inc.app.header',function($view) {
            $view->with('categories', Category::all());
        });

        View::composer('*', function ($view) {
             $cart = session('cart');
             $cartCount = collect($cart)->sum('quantity');
             $view->with('cartCount', $cartCount);
        });

    }
}