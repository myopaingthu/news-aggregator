<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\News\NewsServiceInterface;
use App\Repositories\DataSource\DataSourceRepositoryInterface;

class TheGuardianAPIService implements APIServiceInterface
{
    protected $apiKey;
    protected $newsBaseURL;
    protected $newsService;
    protected $dataSourceRepository;
    protected $limit = 100;
    protected $id = 3;

    public function __construct(
        NewsServiceInterface $newsService,
        DataSourceRepositoryInterface $dataSourceRepository
    ) {
        $this->apiKey = config('services.theguardian.key');
        $this->newsBaseURL = config('services.theguardian.base_url');
        $this->newsService = $newsService;
        $this->dataSourceRepository = $dataSourceRepository;
    }

    /**
     * Fetch news articles from The Guardian API and process them.
     *
     * @return void
     */
    public function fetchNews(): void
    {
        try {
            $lastFetchedAt = $this->dataSourceRepository->getLastFetchedTime($this->id);
            $fromDate = $lastFetchedAt ? $lastFetchedAt->toIso8601String() : today()->subDay(1)->toDateString();

            $queryParams = [
                'api-key' => $this->apiKey,
                'page-size' => $this->limit,
                'from-date' => $fromDate,
                'order-by' => 'newest',
            ];

            $response = Http::get($this->newsBaseURL . 'search', $queryParams);

            $fullUrl = $response->effectiveUri();
            $this->logFullUrl($fullUrl);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch news: ' . $response->body());
            }

            $articles = $response->json()['response']['results'] ?? [];

            if (!empty($articles)) {
                $this->processArticles($articles);
            } else {
                Log::info('No new articles found from The Guardian API.');
            }
        } catch (\Exception $e) {
            Log::error('Error in TheGuardianAPIService::fetchNews: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Process and save articles to the database.
     *
     * @param array $articles
     */
    protected function processArticles(array $articles): void
    {
        $mappedArticles = collect($articles)->map(function ($article) {
            return [
                'source' => [
                    'name' => 'The Guardian',
                ],
                'title' => $article['webTitle'],
                'description' => $article['webTitle'],
                'content' => $article['webTitle'],
                'url' => $article['webUrl'],
                'publishedAt' => $article['webPublicationDate'],
                'data_source_id' => $this->id,
                'category' => $article['sectionName'],
            ];
        })->toArray();

        $this->newsService->saveNews($mappedArticles, $this->id);

        $latestPublishedAt = collect($articles)->max('webPublicationDate');
        $this->dataSourceRepository->updateLastFetchedTime($this->id, $latestPublishedAt);

        Log::info('The Guardian articles successfully saved and last fetched time updated.');
    }

    /**
     * Log the full URL of the API request for debugging purposes.
     *
     * @param string $fullUrl
     */
    protected function logFullUrl(string $fullUrl): void
    {
        Log::info('The Guardian API Request URL: ' . $fullUrl);
    }
}
