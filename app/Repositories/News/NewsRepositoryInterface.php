<?php

namespace App\Repositories\News;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface
{
    public function createOrUpdate(array $data): News;

    /**
     * Fetch filtered news articles.
     *
     * @param array $filters
     * @param int $limit
     */
    public function fetchFilteredNews(array $filters, int $limit): LengthAwarePaginator;
}
