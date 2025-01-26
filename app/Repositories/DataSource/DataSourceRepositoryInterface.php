<?php

namespace App\Repositories\DataSource;

use Illuminate\Database\Eloquent\Collection;

interface DataSourceRepositoryInterface
{
    public function getAll(): Collection;

    public function getLastFetchedTime(int $sourceId): ?\Carbon\Carbon;

    public function updateLastFetchedTime(int $sourceId, string $timestamp): void;
}
