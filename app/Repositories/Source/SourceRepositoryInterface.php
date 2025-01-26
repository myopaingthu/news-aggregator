<?php

namespace App\Repositories\Source;

use Illuminate\Database\Eloquent\Collection;

interface SourceRepositoryInterface
{
    /**
     * @param string $sourceName
     * @return mixed
     */
    public function findByNameOrCreate(string $sourceName);
}
