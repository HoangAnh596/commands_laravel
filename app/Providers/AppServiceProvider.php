<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;
//use File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function boot()
    {
        /* DB::listen(function ($query) {
             Log::info($query->sql, $query->bindings, $query->time);
        }); */
    }
}
