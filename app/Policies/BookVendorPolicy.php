<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookVendorPolicy
{
    use HandlesAuthorization;


    public function view_list(User $user)
    {
        return in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]);
    }

    public function view_create(User $user)
    {
        return in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]);
    }

    public function view_edit(User $user)
    {
        return in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]);
    }

    public function create(User $user)
    {
        return in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]);
    }

    public function update(User $user)
    {
        return in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]);
    }

    public function destroy(User $user)
    {
        return in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]);
    }
}
