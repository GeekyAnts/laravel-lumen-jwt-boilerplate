<?php

namespace App\Providers;

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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $classes = [
            'Auth'
        ];

        foreach ($classes as $class) {
            $this->app->bind(
                "App\Contracts\\{$class}Repository", 
                "App\Repositories\\{$class}Repository"
            );
        }
    }
}
