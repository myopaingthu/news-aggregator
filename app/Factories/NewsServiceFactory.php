<?php

namespace App\Factories;

use App\Services\API\NewsApiService;
use App\Services\API\APIServiceInterface;
use App\Services\API\NewYorkTimesAPIService;
use App\Services\API\TheGuardianAPIService;

class NewsServiceFactory
{
    public static function create(string $dataSourceName): ?APIServiceInterface
    {
        return match ($dataSourceName) {
            'NewsAPI' => app(NewsApiService::class),
            'NewYorkTimes' => app(NewYorkTimesAPIService::class),
            'Guardian' => app(TheGuardianAPIService::class),
            default => null,
        };
    }
}
