<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Http\Resources\SocialActivityResource;
use App\Models\SocialActivity;
use App\Models\User;
use App\Models\Like;
use Auth;

class SocialActivityController extends Controller
{
    public function getUserActivity(User $user, $page = 1, $count = 20)
    {
        if ($user == null) $user = auth()->user();

        $activity = SocialActivity::where('user_id', $user->id)->orderBy("created_at", "desc")->has("item")
            ->skip($count * ($page - 1))->take($count)->get();
        $max_pages = SocialActivity::where('user_id', $user->id)->has("item")->count() / $count;
        $max_pages = ceil($max_pages);

        return response()->json([
            "data" => [
                "activities" => SocialActivityResource::collection($activity),
                "pagination" => SocialActivityController::paginationResult($max_pages, $page)
            ]
        ]);
    }

    public function getActivityFeed($page = 1, $count = 20){
        $user = Auth::user();
        $follower_id_array = $user->following->pluck("id");
        
        $activity_query = SocialActivity::whereIn("user_id", $follower_id_array)
            ->orderBy("created_at", "desc")->has("item");
        $activity = $activity_query->skip($count * ($page - 1))->take($count)->get();
        $max_pages = ceil(SocialActivity::whereIn("user_id", $follower_id_array)
            ->orderBy("created_at", "desc")->has("item")->count() / $count);

        return response()->json([
            "data" => [
                "activity" => SocialActivityResource::collection($activity),
                "pagination" => SocialActivityController::paginationResult($max_pages, $page)
            ]
        ]);
    }

    public function getGlobalFeed($page = 1, $count = 20){
        $activity = SocialActivity::orderBy("created_at", "desc")->has("item")
            ->skip($count * ($page - 1))->take($count)->get();
        $max_pages = ceil(SocialActivity::orderBy("created_at", "desc")->has("item")->count() / $count);

        return response()->json([
            "data" => [
                "activity" => SocialActivityResource::collection($activity),
                "pagination" => SocialActivityController::paginationResult($max_pages, $page)
            ]
        ]);
    }

    public function toggleLike(SocialActivity $activity){
        $like = $activity->likes()->where("user_id", Auth::user()->id);
        if($like) {
            $like->delete();
        }else{
            $like = new Like([
                "user_id" => Auth::user()->id,
            ]);
            $activity->likes()->save($like);
        }
        return response()->json($like);
    }

    private static function paginationResult($max_pages, $page){
        return [
            "max_pages" => $max_pages,
            "prev_page" => ($page > 1) ? $page - 1 : null,
            "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
        ];
    }
}
