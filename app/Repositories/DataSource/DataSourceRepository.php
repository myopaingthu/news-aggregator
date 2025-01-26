<?php

namespace App\Repositories\DataSource;

use Carbon\Carbon;
use App\Models\DataSource;
use Illuminate\Database\Eloquent\Collection;

class DataSourceRepository implements DataSourceRepositoryInterface
{
    protected $model;

    public function __construct(DataSource $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getLastFetchedTime(int $sourceId): ?\Carbon\Carbon
    {
        $dataSource = $this->model->find($sourceId);
        return $dataSource ? $dataSource->last_fetched_at : null;
    }

    public function updateLastFetchedTime(int $sourceId, string $timestamp): void
    {
        $this->model->where('id', $sourceId)->update(['last_fetched_at' => Carbon::parse($timestamp)->toDateTimeString()]);
    }
}
