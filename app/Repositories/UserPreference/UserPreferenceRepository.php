<?php

namespace App\Repositories\UserPreference;

use App\Models\UserPreference;

class UserPreferenceRepository implements UserPreferenceRepositoryInterface
{
    protected $model;

    public function __construct(UserPreference $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }
}
