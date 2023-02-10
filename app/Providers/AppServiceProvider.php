<?php

namespace App\Providers;

use App\Services\Interfaces\NewsServiceInterface;
use App\Services\Interfaces\RssServiceInterface;
use App\Services\NewsService;
use App\Services\RssService;
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
        $this->app->bind(RssServiceInterface::class, RssService::class);
        $this->app->bind(NewsServiceInterface::class, NewsService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
