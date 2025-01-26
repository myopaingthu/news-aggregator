<?php

namespace App\Repositories\Source;

use App\Models\Source;
use Illuminate\Database\Eloquent\Collection;

class SourceRepository implements SourceRepositoryInterface
{
    /**
     * @param string $sourceName
     * @param int $dataSourceId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findByNameOrCreate(string $sourceName)
    {
        $source = Source::query()->where('name', $sourceName)->first();

        if (empty($source)) {
            $source = Source::create([
                'name' => $sourceName,
            ]);
        }
        return $source;
    }
}
