<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckUserRole;
use App\Role\RoleChecker;


class CheckUserRoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register Middleware in App Service Provider
        $this->app->singleton(CheckUserRole::class, function(Application $app) {
            return new CheckUserRole($this->app->make(RoleChecker::class));
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
