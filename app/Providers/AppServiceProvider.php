<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NotificationProvider;
use App\Services\SlackService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // For flexibility, we can swap the SlackService later with some other service if we need in future.
        $this->app->bind(NotificationProvider::class, SlackService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
