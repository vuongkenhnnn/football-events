<?php

namespace App\Providers;

use App\Repositories\FootballMatchRepository;
use App\Repositories\Interfaces\FootballMatchRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FootballMatchRepositoryInterface::class, FootballMatchRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
