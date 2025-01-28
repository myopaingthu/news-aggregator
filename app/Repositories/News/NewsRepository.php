<?php

namespace App\Repositories\News;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\News;

class NewsRepository implements NewsRepositoryInterface
{
    protected $model;

    /**
     * NewsRepository constructor.
     *
     * @param \App\Models\News $model
     */
    public function __construct(News $model)
    {
        $this->model = $model;
    }

    /**
     * Create or update a news article based on the slug.
     *
     * @param array $data News data to be stored or updated.
     * @return \App\Models\News
     */
    public function createOrUpdate(array $data): News
    {
        return $this->model->updateOrCreate(
            ['slug' => \Str::slug($data['title'])],
            $data
        );
    }

    /**
     * Fetch filtered news articles with pagination.
     *
     * @param array $filters Filters to apply (e.g., category, date, source).
     * @param int $limit Number of results per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchFilteredNews(array $filters, int $limit): LengthAwarePaginator
    {
        return $this->model
            ->with(['source'])
            ->filter($filters)
            ->orderBy('published_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Fetch search results for news articles with pagination.
     *
     * @param string $query Search query for news articles.
     * @param int $limit Number of results per page.
     * @param int $page Current page number.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchSearchResults(string $query, int $limit, int $page): LengthAwarePaginator
    {
        return $this->model
            ->with(['source'])
            ->search($query)
            ->orderBy('published_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }
}
