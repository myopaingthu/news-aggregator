<?php

namespace App\Services\News;

use Carbon\Carbon;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Source\SourceRepositoryInterface;

class NewsService implements NewsServiceInterface
{
    protected $newsRepository;
    protected $sourceRepositoryInterface;

    public function __construct(NewsRepositoryInterface $newsRepository, SourceRepositoryInterface $sourceRepositoryInterface)
    {
        $this->newsRepository = $newsRepository;
        $this->sourceRepositoryInterface = $sourceRepositoryInterface;
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
}
