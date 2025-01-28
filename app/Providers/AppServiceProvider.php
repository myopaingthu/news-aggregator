<?php

namespace App\Providers;

use App\Models\UserPreference;
use App\Policies\UserPreferencePolicy;
use Illuminate\Http\Response;
use App\Services\News\NewsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
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
     *
     * @return void
     */
    public function register(): void
    {
        $this->bindInterfaces();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Gate::policy(UserPreference::class, UserPreferencePolicy::class);

        $this->addResponseMacros();
        $this->logQueries();
    }

    /**
     * Bind repository interfaces to their implementations.
     *
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

    /**
     * Add response macros for success and error responses.
     *
     * @return void
     */
    private function addResponseMacros(): void
    {
        Response::macro('success', function ($status_code, $data = null, $message = null) {
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $data,
                'status_code' => $status_code,
            ], $status_code);
        });

        Response::macro('error', function ($message, $status_code, $data = null) {
            return response()->json([
                'status' => false,
                'message' => $message,
                'errors' => $data,
                'status_code' => $status_code,
            ], $status_code);
        });
    }

    /**
     * Log all database queries for debugging.
     *
     * @return void
     */
    private function logQueries(): void
    {
        DB::listen(
            function ($sql) {
                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }
                $query = str_replace(['%', '?'], ['%%', '%s'], $sql->sql);
                $query = vsprintf($query, $sql->bindings);
                Log::channel('querylog')->debug($query);
            }
        );
    }
}
