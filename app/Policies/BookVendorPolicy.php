<?php

namespace App\Policies;

use App\Models\BookVendor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

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

    public function use(User $user, BookVendor $vendor)
    {
        if ($vendor->public == false && $vendor->user_id != $user->id) {
            return Response::deny("You do not have access to this vendor.");
        }
        return Response::allow();
    }

    public function api_update(User $user, BookVendor $vendor)
    {
        if ($vendor->user_id == null || $vendor->user_id != $user->id) {
            return Response::deny("You do not have access to this vendor.");
        }
        return Response::allow();
    }

    public function api_destroy(User $user, BookVendor $vendor)
    {
        if ($vendor->user_id == null || $vendor->user_id != $user->id) {
            return Response::deny("You do not have access to this vendor.");
        }
        return Response::allow();
    }
}
