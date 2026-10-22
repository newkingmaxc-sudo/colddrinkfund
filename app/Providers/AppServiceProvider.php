<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        if (request()->server('HTTP_X_FORWARDED_PROTO') == 'https' || request()->isSecure() || (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'trycloudflare.com'))) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
