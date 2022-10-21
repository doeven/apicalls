<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Notifications\ResetPassword;

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
        //
        Schema::defaultstringLength(191);
        // ResetPassword::createUrlUsing(function($notifiable, $token){
           // return 'http://127.0.0.1:5000/reset-password/{$token}?email={$notifiable->getEmailForPasswordReset()}';
        // });
    }
}
