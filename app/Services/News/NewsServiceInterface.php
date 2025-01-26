<?php

namespace App\Services\News;

interface NewsServiceInterface
{
    public function saveNews(array $articles, int $dataSourceId): void;
}
