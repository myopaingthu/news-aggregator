<?php

namespace App\Repositories\DataSource;

use Carbon\Carbon;
use App\Models\DataSource;
use Illuminate\Database\Eloquent\Collection;

class DataSourceRepository implements DataSourceRepositoryInterface
{
    /**
     * The model instance.
     *
     * @var \App\Models\DataSource
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @param \App\Models\DataSource $model
     */
    public function __construct(DataSource $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve all data sources.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get the last fetched time for a specific data source.
     *
     * @param int $sourceId
     * @return \Carbon\Carbon|null
     */
    public function getLastFetchedTime(int $sourceId): ?Carbon
    {
        $dataSource = $this->model->find($sourceId);
        return $dataSource ? $dataSource->last_fetched_at : null;
    }

    /**
     * Update the last fetched time for a specific data source.
     *
     * @param int $sourceId
     * @param string $timestamp
     * @return void
     */
    public function updateLastFetchedTime(int $sourceId, string $timestamp): void
    {
        $this->model->where('id', $sourceId)
            ->update(['last_fetched_at' => Carbon::parse($timestamp)->toDateTimeString()]);
    }
}
