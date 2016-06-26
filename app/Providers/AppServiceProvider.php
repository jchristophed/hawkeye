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
        $modules = array('Flat', 'Tenant', 'Contract', 'Residence', 'Document', 'Block', 'View');

        foreach ($modules as $module) {

            $this->app->bind(
                'App\Repositories\\' . $module . 'RepositoryInterface',
                'App\Repositories\\' . $module . 'Repository'
            );

        }
    }
}
