<?php

namespace App\Repositories\Source;

use Illuminate\Database\Eloquent\Collection;

interface SourceRepositoryInterface
{
    /**
     * Find a source by name or create it if it doesn't exist.
     *
     * @param string $sourceName
     * @return mixed
     */
    public function findByNameOrCreate(string $sourceName);

    /**
     * Get all sources.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection;
}
