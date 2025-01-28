<?php

namespace App\Repositories\Source;

use App\Models\Source;
use Illuminate\Database\Eloquent\Collection;

class SourceRepository implements SourceRepositoryInterface
{
    protected $model;

    public function __construct(Source $model)
    {
        $this->model = $model;
    }

    /**
     * Find a source by name or create it if it doesn't exist.
     *
     * @param string $sourceName
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findByNameOrCreate(string $sourceName)
    {
        $source = $this->model->query()->where('name', $sourceName)->first();

        if (empty($source)) {
            $source = $this->model->create([
                'name' => $sourceName,
            ]);
        }
        return $source;
    }

    /**
     * Get all sources.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }
}
