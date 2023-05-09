<?php

namespace App\Providers;

use Illuminate\Auth\RequestGuard;
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
//        ini_set('post_max_size', '500M');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        RequestGuard::macro('logout', function ($guard){
            auth($guard)
                ->user()
                ->tokens()
                ->where('id', auth($guard)->user()->currentAccessToken()->id)
                ->update(['expires_at' => now()]);
        });
    }
}
