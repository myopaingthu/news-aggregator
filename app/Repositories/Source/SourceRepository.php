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
     * @param string $sourceName
     * @param int $dataSourceId
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
}
