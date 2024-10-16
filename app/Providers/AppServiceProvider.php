<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\Gate;
//use Illuminate\Pagination\Paginator;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*Gate::before(function ($player, $ability) {
            return $player->hasRole('Super Admin') ? true : null;
        });
        
        Paginator::useBootstrapFive();*/
        /*$this->registerPolicies();
        Passport::routes();*/
        Passport::enablePasswordGrant();
    }
}
