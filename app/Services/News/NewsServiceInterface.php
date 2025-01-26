<?php

namespace App\Services\News;

use Illuminate\Pagination\LengthAwarePaginator;

interface NewsServiceInterface
{
    public function saveNews(array $articles, int $dataSourceId): void;

    public function getFilteredNews(array $filters, int $limit): LengthAwarePaginator;
}
