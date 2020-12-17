<?php

namespace App\Providers;

use App\Translit\Translit;
use Illuminate\Support\ServiceProvider;

class TranslitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('translit',function(){
            return new Translit();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
