<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\News\NewsServiceInterface;
use App\Repositories\DataSource\DataSourceRepositoryInterface;

class NewYorkTimesAPIService implements APIServiceInterface
{
    protected $apiKey;
    protected $newsBaseURL;
    protected $newsService;
    protected $dataSourceRepository;
    protected $limit = 20;
    protected $id = 2;

    public function __construct(
        NewsServiceInterface $newsService,
        DataSourceRepositoryInterface $dataSourceRepository
    ) {
        $this->apiKey = config('services.nyt.key');
        $this->newsBaseURL = config('services.nyt.base_url');
        $this->newsService = $newsService;
        $this->dataSourceRepository = $dataSourceRepository;
    }

    /**
     * Fetch news articles from the New York Times API and process them.
     *
     * @return void
     */
    public function fetchNews(): void
    {
        try {
            $lastFetchedAt = $this->dataSourceRepository->getLastFetchedTime($this->id);
            $fromDate = $lastFetchedAt ? $lastFetchedAt->toDateString() : today()->subDay(1)->toDateString();

            $queryParams = [
                'api-key' => $this->apiKey,
                'begin_date' => str_replace('-', '', $fromDate),
                'sort' => 'newest',
                'page' => 0,
            ];

            $articles = [];
            $totalArticles = 0;

            while ($totalArticles < $this->limit) {
                $response = Http::get($this->newsBaseURL . 'search/v2/articlesearch.json', $queryParams);

                $fullUrl = $response->effectiveUri();
                $this->logFullUrl($fullUrl);

                if (!$response->successful()) {
                    throw new \Exception('Failed to fetch news: ' . $response->body());
                }

                $newArticles = $response->json()['response']['docs'] ?? [];

                $articles = array_merge($articles, $newArticles);
                $totalArticles = count($articles);

                if (count($newArticles) < 10) {
                    break;
                }

                $queryParams['page'] += 1;
            }

            if (!empty($articles)) {
                $this->processArticles(array_slice($articles, 0, $this->limit));
            } else {
                Log::info('No new articles found from the NYT API.');
            }
        } catch (\Exception $e) {
            Log::error('Error in NewYorkTimesAPIService::fetchNews: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Log the full URL of the API request for debugging purposes.
     *
     * @param string $fullUrl
     */
    protected function logFullUrl(string $fullUrl): void
    {
        Log::info('NYT API Request URL: ' . $fullUrl);
    }

    /**
     * Process and save articles to the database.
     *
     * @param array $articles
     */
    protected function processArticles(array $articles): void
    {
        $articles = array_reverse($articles);
        $mappedArticles = collect($articles)->map(function ($article) {
            return [
                'title' => $article['headline']['main'] ?? 'No Title',
                'description' => $article['abstract'] ?? null,
                'url' => $article['web_url'],
                'publishedAt' => $article['pub_date'],
                'data_source_id' => $this->id,
                'source' => [
                    'name' => $article['source'],
                ],
                'author' => $article['byline']['original'] ?? null,
                'content' => $article['lead_paragraph'] ?? null,
                'image_url' => $article['multimedia'][0]['url'] ?? null,
                'category' => $article['section_name'] ?? null,
            ];
        })->toArray();

        $this->newsService->saveNews($mappedArticles, $this->id);

        $latestPublishedAt = collect($articles)->max('pub_date');
        $this->dataSourceRepository->updateLastFetchedTime($this->id, $latestPublishedAt);

        Log::info('NYT articles successfully saved and last fetched time updated.');
    }
}
