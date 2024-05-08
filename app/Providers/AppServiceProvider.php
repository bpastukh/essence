<?php

namespace App\Providers;

use App\Service\GetSummaryService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GetSummaryService::class, function (Application $app) {
            return new GetSummaryService($app['config']['app']['openAiApiKey']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
