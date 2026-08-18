<?php

namespace Modules\Marketing\Providers;

use Illuminate\Support\ServiceProvider;
use Database\Seeders\DatabaseSeeder;

class MarketingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // // Merge a custom config file
        //$this->mergeConfigFrom(__DIR__ . '/../config.php', 'user');

        $this->app->register(RouteServiceProvider::class);
    }
}
