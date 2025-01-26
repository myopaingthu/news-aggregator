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
