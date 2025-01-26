<?php

namespace App\Repositories\News;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\News;

class NewsRepository implements NewsRepositoryInterface
{
    protected $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function createOrUpdate(array $data): News
    {
        return $this->model->updateOrCreate(
            ['slug' => \Str::slug($data['title'])],
            $data
        );
    }

    /**
     * Fetch filtered news articles.
     *
     * @param array $filters
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function fetchFilteredNews(array $filters, int $limit): LengthAwarePaginator
    {
        return $this->model
            ->filter($filters)
            ->orderBy('published_at', 'desc')
            ->paginate($limit);
    }
}
