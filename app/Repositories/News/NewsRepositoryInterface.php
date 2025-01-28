<?php

namespace App\Repositories\News;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface
{
    /**
     * Create or update a news article.
     *
     * @param array $data
     * @return \App\Models\News
     */
    public function createOrUpdate(array $data): News;

    /**
     * Fetch filtered news articles based on the provided filters.
     *
     * @param array $filters Filters to apply (e.g., category, date, source).
     * @param int $limit Number of results per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchFilteredNews(array $filters, int $limit): LengthAwarePaginator;

    /**
     * Fetch search results for news articles based on a search query.
     *
     * @param string $query Search keyword(s).
     * @param int $limit Number of results per page.
     * @param int $page Page number for pagination.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchSearchResults(string $query, int $limit, int $page): LengthAwarePaginator;
}
