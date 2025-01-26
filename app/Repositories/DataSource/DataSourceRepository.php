<?php

namespace App\Repositories\DataSource;

use Carbon\Carbon;
use App\Models\DataSource;
use Illuminate\Database\Eloquent\Collection;

class DataSourceRepository implements DataSourceRepositoryInterface
{
    public function getAll(): Collection
    {
        return DataSource::all();
    }

    public function getLastFetchedTime(int $sourceId): ?\Carbon\Carbon
    {
        $dataSource = DataSource::find($sourceId);
        return $dataSource ? $dataSource->last_fetched_at : null;
    }

    public function updateLastFetchedTime(int $sourceId, string $timestamp): void
    {
        DataSource::where('id', $sourceId)->update(['last_fetched_at' => Carbon::parse($timestamp)->toDateTimeString()]);
    }
}
