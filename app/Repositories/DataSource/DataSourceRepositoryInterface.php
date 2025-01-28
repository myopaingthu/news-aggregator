<?php

namespace App\Repositories\DataSource;

use Illuminate\Database\Eloquent\Collection;

interface DataSourceRepositoryInterface
{
    /**
     * Retrieve all data sources.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection;

    /**
     * Get the last fetched time for a specific data source.
     *
     * @param int $sourceId
     * @return \Carbon\Carbon|null
     */
    public function getLastFetchedTime(int $sourceId): ?\Carbon\Carbon;

    /**
     * Update the last fetched time for a specific data source.
     *
     * @param int $sourceId
     * @param string $timestamp
     * @return void
     */
    public function updateLastFetchedTime(int $sourceId, string $timestamp): void;
}
