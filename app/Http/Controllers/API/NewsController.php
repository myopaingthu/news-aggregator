<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Repositories\UserPreference\UserPreferenceRepositoryInterface;
use App\Services\News\NewsServiceInterface;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    /**
     * The news service instance.
     *
     * @var \App\Services\News\NewsServiceInterface
     */
    private $newsService;

    /**
     * The default number of articles per page.
     *
     * @var int
     */
    private $limit = 10;

    /**
     * The default page number.
     *
     * @var int
     */
    private $default_page = 1;

    /**
     * The user preference repository instance.
     *
     * @var \App\Repositories\UserPreference\UserPreferenceRepositoryInterface
     */
    private $userPreferenceRepository;

    /**
     * Initialize the controller instance.
     *
     * @param \App\Services\News\NewsServiceInterface $newsService
     * @param \App\Repositories\UserPreference\UserPreferenceRepositoryInterface $userPreferenceRepository
     */
    public function __construct(NewsServiceInterface $newsService, UserPreferenceRepositoryInterface $userPreferenceRepository)
    {
        $this->newsService = $newsService;
        $this->userPreferenceRepository = $userPreferenceRepository;
    }

    /**
     * Generate a cache key for storing news data.
     *
     * @param int $limit
     * @param int $page
     * @param array $filters
     * @param string $query
     * @return string
     */
    protected function generateCacheKey(int $limit, int $page, array $filters = [], string $query = ''): string
    {
        if (!empty($filters)) {
            return 'news_' . md5(json_encode($filters) . "_limit_{$limit}_page_{$page}");
        } else if (!empty($query)) {
            return 'news_search_' . md5($query . "_limit_{$limit}_page_{$page}");
        }
        return 'news_' . md5("limit_{$limit}_page_{$page}");
    }

    /**
     * Display a list of news articles.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $filters = $request->only(['date', 'category', 'source', 'preference_id']);
        $limit = $request->get('limit', $this->limit);
        $page = $request->get('page', $this->default_page);

        if (isset($filters['preference_id']) && !($this->isValidPreferenceId($filters['preference_id']))) {
            return Response::error('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }

        $cacheKey = $this->generateCacheKey($limit, $page, $filters);

        $articles = cache()->remember($cacheKey, now()->addMinutes(10), function () use ($filters, $limit) {
            return $this->newsService->getFilteredNews($filters, $limit);
        });

        return NewsResource::collection($articles);
    }

    /**
     * Search for news articles based on a query.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $limit = $request->get('limit', $this->limit);
        $page = $request->get('page', $this->default_page);

        if (empty($query)) {
            return Response::error('Search query is required.', Response::HTTP_BAD_REQUEST);
        }

        $cacheKey = $this->generateCacheKey($limit, $page, [], $query);

        $articles = cache()->remember($cacheKey, now()->addMinutes(10), function () use ($query, $limit, $page) {
            return $this->newsService->searchNews($query, $limit, $page);
        });

        return NewsResource::collection($articles);
    }

    /**
     * Check if the preference_id exists and belongs to the authenticated user.
     *
     * @param int|null $preferenceId
     * @return bool
     */
    private function isValidPreferenceId(?int $preferenceId): bool
    {
        if (!$preferenceId) {
            return true;
        }

        if (!auth('sanctum')->check()) {
            return false;
        }

        $userPreference = $this->userPreferenceRepository->getById($preferenceId);
        if ($userPreference && $userPreference->user_id !== auth('sanctum')->id()) {
            return false;
        }

        return true;
    }
}
