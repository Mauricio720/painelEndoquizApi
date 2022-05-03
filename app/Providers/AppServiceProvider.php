<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;

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
        Paginator::useBootstrap();
        Config::set('tokenPagamento', "APP_USR-6076604613041074-101814-1ab1c666ed8db1d611179f8d83dc8ae0-578315376"); 
        Config::set('premiumValue',0.05);
    }
}
