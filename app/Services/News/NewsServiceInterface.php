<?php

namespace App\Services\News;

use Illuminate\Pagination\LengthAwarePaginator;

interface NewsServiceInterface
{
    /**
     * Save or update news articles in the database.
     *
     * @param array $articles
     * @param int $dataSourceId
     * @return void
     */
    public function saveNews(array $articles, int $dataSourceId): void;

    /**
     * Retrieve filtered news articles based on the specified filters.
     *
     * @param array $filters
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getFilteredNews(array $filters, int $limit): LengthAwarePaginator;

    /**
     * Search news articles based on the provided query.
     *
     * @param string $query
     * @param int $limit
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function searchNews(string $query, int $limit, int $page): LengthAwarePaginator;
}
