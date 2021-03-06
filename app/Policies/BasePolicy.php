<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Run checks before calling the individual policies
     *
     * @param User|null $authUser
     * @return bool
     */
    public function before(?User $authUser)
    {
        // Allow users with the super admin role to perform any action
        if ($authUser && $authUser->hasRole('super-admin')) {
            return true;
        }
    }
}
