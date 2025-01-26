<?php

namespace App\Repositories\UserPreference;

use Illuminate\Database\Eloquent\Collection;

interface UserPreferenceRepositoryInterface
{
    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getById(int $id);
}
