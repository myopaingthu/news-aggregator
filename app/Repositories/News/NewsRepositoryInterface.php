<?php

namespace App\Repositories\News;

use App\Models\News;

interface NewsRepositoryInterface
{
    public function createOrUpdate(array $data): News;
}
