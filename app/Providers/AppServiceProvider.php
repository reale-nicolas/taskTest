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
        $this->app
            ->when('App\Services\TaskServices')
            ->needs('App\Interfaces\RepositoryInterface')
            ->give('App\Cache\TaskCache');
        
        $this->app
            ->when('App\Cache\TaskCache')
            ->needs('App\Interfaces\RepositoryInterface')
            ->give('App\Repositories\TaskRepository');
    }
}
