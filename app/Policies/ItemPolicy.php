<?php

namespace App\Policies;

use App\Models\BookVendor;
use App\Models\Collection;
use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ItemPolicy
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

    public function access(User $user, Item $item){
        if ($item->collection->user->id != $user->id) {
            return Response::deny("You do not own this item.");
        }
        return Response::allow(); 
    }

}
