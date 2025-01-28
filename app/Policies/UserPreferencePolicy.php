<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserPreference;

class UserPreferencePolicy
{
    /**
     * Determine if the user can view the preferences.
     */
    public function update(User $user, UserPreference $preference)
    {
        return $user->id === $preference->user_id;
    }

    /**
     * Determine if the user can delete the given preference.
     */
    public function delete(User $user, UserPreference $preference)
    {
        return $user->id === $preference->user_id;
    }
}
