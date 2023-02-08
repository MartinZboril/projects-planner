<?php

namespace App\Providers;

use App\Services\FlashService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       view()->composer('*',function($view) {
            $view->with('aflashService', new FlashService);
        });
    }
}
