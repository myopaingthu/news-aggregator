<?php

namespace App\Services\News;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Source\SourceRepositoryInterface;
use App\Repositories\UserPreference\UserPreferenceRepositoryInterface;

class NewsService implements NewsServiceInterface
{
    protected $newsRepository;
    protected $sourceRepositoryInterface;
    protected $userPreferenceRepository;

    public function __construct(NewsRepositoryInterface $newsRepository, SourceRepositoryInterface $sourceRepositoryInterface, UserPreferenceRepositoryInterface $userPreferenceRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->sourceRepositoryInterface = $sourceRepositoryInterface;
        $this->userPreferenceRepository = $userPreferenceRepository;
    }

    /**
     * Save or update news articles in the database.
     *
     * @param array $articles
     * @param int $dataSourceId
     * @return void
     */
    public function saveNews(array $articles, int $dataSourceId): void
    {
        foreach ($articles as $article) {
            $source_id = null;
            if (!empty($article['source']['name'])) {
                $source = $this->sourceRepositoryInterface->findByNameOrCreate($article['source']['name']);
                $source_id = $source->id;
            }

            $this->newsRepository->createOrUpdate([
                'author' => $article['author'] ?? null,
                'title' => $article['title'],
                'description' => $article['description'],
                'content' => $article['content'],
                'url' => $article['url'],
                'image_url' => $article['urlToImage'] ?? null,
                'published_at' => Carbon::parse($article['publishedAt'])->toDateTimeString(),
                'source_id' => $source_id,
                'data_source_id' => $dataSourceId,
                'category' => $article['category'] ?? null,
            ]);
        }
    }

    /**
     * Retrieve filtered news articles based on the specified filters.
     *
     * @param array $filters
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getFilteredNews(array $filters, int $limit): LengthAwarePaginator
    {
        if (!empty($filters['preference_id'])) {
            $preferences = $this->getPreferencesById($filters['preference_id']);
            if ($preferences) {
                $filters = array_merge($filters, $preferences);
            }
        }

        return $this->newsRepository->fetchFilteredNews($filters, $limit);
    }

    /**
     * Search news articles based on the provided query.
     *
     * @param string $query
     * @param int $limit
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function searchNews(string $query, int $limit, int $page): LengthAwarePaginator
    {
        return $this->newsRepository->fetchSearchResults($query, $limit, $page);
    }

    /**
     * Retrieve preferences by ID from the user_preferences table.
     *
     * @param int $preferenceId
     * @return array|null
     */
    protected function getPreferencesById(int $preferenceId): ?array
    {
        $userPreference = $this->userPreferenceRepository->getById($preferenceId);

        if ($userPreference && $userPreference->preferences) {
            return json_decode($userPreference->preferences, true);
        }

        return null;
    }
}
