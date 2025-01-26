<?php

namespace App\Repositories\News;

use App\Models\News;

class NewsRepository implements NewsRepositoryInterface
{
    public function createOrUpdate(array $data): News
    {
        return News::updateOrCreate(
            ['slug' => \Str::slug($data['title'])],
            $data
        );
    }
}
