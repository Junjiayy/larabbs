<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param  User $current_user
     * @param  User $user
     * @return bool
     */
    public function update( User $current_user,User $user )
    {
        return $current_user->id === $user->id;
    }
}
