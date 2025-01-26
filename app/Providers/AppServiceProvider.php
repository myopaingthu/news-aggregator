<?php

namespace App\Providers;

use App\Services\News\NewsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use App\Repositories\News\NewsRepository;
use App\Services\News\NewsServiceInterface;
use App\Repositories\Source\SourceRepository;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\DataSource\DataSourceRepository;
use App\Repositories\Source\SourceRepositoryInterface;
use App\Repositories\UserPreference\UserPreferenceRepository;
use App\Repositories\DataSource\DataSourceRepositoryInterface;
use App\Repositories\UserPreference\UserPreferenceRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->bindInterfaces();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            Log::info('SQL Query: ' . $query->sql);
            Log::info('Bindings: ' . implode(', ', $query->bindings));
            Log::info('Time: ' . $query->time . 'ms');
        });
        
    }

    /**
     * @return void
     */
    private function bindInterfaces(): void
    {
        $this->app->bind(DataSourceRepositoryInterface::class, DataSourceRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(NewsServiceInterface::class, NewsService::class);
        $this->app->bind(SourceRepositoryInterface::class, SourceRepository::class);
        $this->app->bind(UserPreferenceRepositoryInterface::class, UserPreferenceRepository::class);
    }
}
