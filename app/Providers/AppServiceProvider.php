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
        view()->composer('*', function ($view) 
        {

            if(\Auth::check()) {

                $app_usage = '';
    
                if(!empty(auth()->user()->preference->app_usage)) {
    
                    $app_usage = auth()->user()->preference->app_usage;
    
                }
    
                $view->with([
                    'app_usage' => $app_usage, 
                ]); 
    
            }
               
        });  
    }
}
