<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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
        // Check if the session has a locale set and apply it
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            
            // Ensure the locale is valid
            if (in_array($locale, ['en', 'id'])) {
                App::setLocale($locale);
            }
        }
    }
}
