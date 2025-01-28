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
     * Get user preference by ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Get user preferences by user ID.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Create a new user preference.
     *
     * @param int $userId
     * @param array $data
     * @return \App\Models\UserPreference
     */
    public function create(int $userId, array $data)
    {
        return $this->model->create([
            'user_id' => $userId,
            'preferences' => json_encode($data['preferences']),
        ]);
    }

    /**
     * Update an existing user preference.
     *
     * @param int $preferenceId
     * @param int $userId
     * @param array $data
     * @return \App\Models\UserPreference
     */
    public function update(int $preferenceId, int $userId, array $data)
    {
        $userPreference = $this->model->where('user_id', $userId)->findOrFail($preferenceId);
        $userPreference->update(['preferences' => json_encode($data['preferences'])]);
        return $userPreference;
    }

    /**
     * Delete a user preference.
     *
     * @param int $preferenceId
     * @param int $userId
     * @return void
     */
    public function delete(int $preferenceId, int $userId)
    {
        $userPreference = $this->model->where('user_id', $userId)->findOrFail($preferenceId);
        $userPreference->delete();
    }
}
