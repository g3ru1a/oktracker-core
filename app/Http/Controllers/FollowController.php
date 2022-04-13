<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserResultResource;
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

    public function searchUsers($query, $page = 1, $count = 30)
    {
        $users = User::where("name", "LIKE", "%" . $query . "%")
            ->skip($count * ($page - 1))->take($count)->get();

        $max_pages = User::where("name", "LIKE", "%" . $query . "%")->count() / $count;
        $max_pages = ceil($max_pages);

        $pagination_result = [
            "max_pages" => $max_pages,
            "prev_page" => ($page > 1) ? $page - 1 : null,
            "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
        ];
        return response()->json([
            "data" => [
                "users" => UserResultResource::collection($users),
                "pagination" => $pagination_result
            ]
        ]);
    }
}
