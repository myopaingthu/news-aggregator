<?php

namespace App\Repositories\UserPreference;

use Illuminate\Database\Eloquent\Collection;

interface UserPreferenceRepositoryInterface
{
    /**
     * Get user preference by ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getById(int $id);

    /**
     * Get user preferences by user ID.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser(int $userId);

    /**
     * Create a new user preference.
     *
     * @param int $userId
     * @param array $preferences
     * @return \App\Models\UserPreference
     */
    public function create(int $userId, array $preferences);

    /**
     * Update an existing user preference.
     *
     * @param int $preferenceId
     * @param int $userId
     * @param array $preferences
     * @return \App\Models\UserPreference
     */
    public function update(int $preferenceId, int $userId, array $preferences);

    /**
     * Delete a user preference.
     *
     * @param int $preferenceId
     * @param int $userId
     * @return void
     */
    public function delete(int $preferenceId, int $userId);
}
