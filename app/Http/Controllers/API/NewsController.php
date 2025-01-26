<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use App\Services\News\NewsServiceInterface;

class NewsController extends Controller
{
    private $newsService;
    private $limit = 10;
    private $default_page = 1;

    public function __construct(NewsServiceInterface $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['date', 'category', 'source', 'preference_id']);
        $limit = $request->get('limit', $this->limit);
        $page = $request->get('page', $this->default_page);

        $cacheKey = $this->generateCacheKey($filters, $limit, $page);

        //$articles = cache()->remember($cacheKey, now()->addMinutes(10), function () use ($filters, $limit) {
            return $this->newsService->getFilteredNews($filters, $limit);
        //});

        return response()->json([
            'data' => $articles,
        ]);
    }

    protected function generateCacheKey(array $filters, int $limit, int $page): string
    {
        return 'news_' . md5(json_encode($filters) . "_limit_{$limit}_page_{$page}");
    }
}
