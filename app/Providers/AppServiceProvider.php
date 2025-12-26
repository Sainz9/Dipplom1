<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- Энийг нэмэх хэрэгтэй байсан

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
        // Хэрэв та production дээр HTTPS алдаа засуулах гэж байгаа бол энэ кодыг нээгээрэй:
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.inc.app.header',function($view) {
            // Category model байхгүй бол алдаа гарахаас сэргийлж try catch хийх эсвэл байгаа эсэхийг шалгах
            $view->with('categories', Category::all());
        });

        View::composer('*', function ($view) {
             $cart = session('cart');
             // cart хоосон байх үед алдаа гарахаас сэргийлэв
             $cartCount = $cart ? collect($cart)->sum('quantity') : 0;
             $view->with('cartCount', $cartCount);
        });
    }
}