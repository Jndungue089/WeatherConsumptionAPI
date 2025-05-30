<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $user, User $target)
    {
        return $user->id === $target->id;
    }

    public function delete(User $user, User $target)
    {
        return $user->id === $target->id;
    }
}
