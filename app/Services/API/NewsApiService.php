<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\News\NewsServiceInterface;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\DataSource\DataSourceRepositoryInterface;

class NewsApiService implements APIServiceInterface
{
    protected $apiKey;
    protected $newsService;
    protected $newsRepository;
    protected $dataSourceRepository;
    protected $newsBaseURL;
    protected $domains = ['bbc.co.uk', 'techcrunch.com', 'engadget.com'];
    protected $limit = 100;
    protected $id = 1;

    public function __construct(
        NewsServiceInterface $newsService,
        NewsRepositoryInterface $newsRepository,
        DataSourceRepositoryInterface $dataSourceRepository
    ) {
        $this->apiKey = config('services.newsapi.key');
        $this->newsBaseURL = config('services.newsapi.base_url');
        $this->newsService = $newsService;
        $this->newsRepository = $newsRepository;
        $this->dataSourceRepository = $dataSourceRepository;
    }

    /**
     * Fetch news articles from the News API and process them.
     *
     * @return void
     */
    public function fetchNews(): void
    {
        try {
            $lastFetchedAt = $this->dataSourceRepository->getLastFetchedTime($this->id);
            $fromDate = $lastFetchedAt ? $lastFetchedAt->toIso8601String() : today()->subDay(1)->toDateString();

            $queryParams = [
                'apiKey' => $this->apiKey,
                'domains' => $this->getDomainsAsString(),
                'pageSize' => $this->limit,
                'from' => $fromDate,
                'sortBy' => 'publishedAt',
            ];

            $response = Http::get($this->newsBaseURL, $queryParams);

            $fullUrl = $response->effectiveUri();
            $this->logFullUrl($fullUrl);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch news: ' . $response->body());
            }

            $articles = $response->json()['articles'] ?? [];

            if (!empty($articles)) {
                $this->processArticles($articles);
            } else {
                Log::info('No new articles found from the API.');
            }
        } catch (\Exception $e) {
            Log::error('Error in NewsApiService::fetchNews: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Convert domains array to a comma-separated string.
     *
     * @return string
     */
    protected function getDomainsAsString(): string
    {
        return implode(',', $this->domains);
    }

    /**
     * Log the full URL of the API request for debugging purposes.
     *
     * @param string $fullUrl
     */
    protected function logFullUrl(string $fullUrl): void
    {
        Log::info('News API Request URL: ' . $fullUrl);
    }

    /**
     * Process and save articles to the database.
     *
     * @param array $articles
     */
    protected function processArticles(array $articles): void
    {
        $articles = array_reverse($articles);

        $this->newsService->saveNews($articles, $this->id);

        $latestPublishedAt = collect($articles)->max('publishedAt');
        $this->dataSourceRepository->updateLastFetchedTime($this->id, $latestPublishedAt);

        Log::info('News articles successfully saved and last fetched time updated.');
    }
}
