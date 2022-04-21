<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CollectionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Collection $collection){
        if ($collection->user->id != $user->id) {
            return Response::deny("You do not own this collection.");
        }
        return Response::allow(); 
    }

    public function create(User $user)
    {
        if ($user->role_id === Role::USER && count($user->collections) >= 5) {
            return Response::deny("Users cannot have more than 5 collections.", 422);
        }
        return Response::allow();
    }

    public function update(User $user, Collection $collection)
    {
        if ($collection->user->id != $user->id) {
            return Response::deny("You do not own this collection.");
        }
        return Response::allow();
    }

    public function destroy(User $user, Collection $collection)
    {
        if ($collection->user->id != $user->id) {
            return Response::deny("You do not own this collection.");
        }
        return Response::allow();
    }
}
