<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user){
        $f = Follower::create([
            "user_id" => auth()->user()->id,
            "follow_id" => $user->id
        ]);
        return $f;
    }

    public function unfollow(User $user){
        $f = Follower::where("user_id", auth()->user()->id)
            ->where("follow_id", $user->id)->first();
        if($f) $f->delete();
        return $f;
    }

    public function getFollowers(User $user = null){
        if($user == null) $user = Auth::user();

        $f = $user->followers;
        return UserResource::collection($f);
    }

    public function getFollowing(User $user = null)
    {
        if ($user == null) $user = Auth::user();

        $f = $user->following;
        return UserResource::collection($f);
    }
}
