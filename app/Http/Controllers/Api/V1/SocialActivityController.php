<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Http\Resources\SocialActivityResource;
use App\Http\Resources\UserResultResource;
use App\Models\Collection;
use App\Models\Item;
use App\Models\SocialActivity;
use App\Models\User;
use App\Models\Follower;
use App\Models\Like;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SocialActivityController extends Controller
{
    public function getUserActivity($page = 1, User $user = null, $count = 20)
    {
        if ($user == null) $user = auth()->user();

        $activity = SocialActivity::where('user_id', $user->id)->orderBy("created_at", "desc")->has("item")
            ->skip($count * ($page - 1))->take($count)->get();
        $max_pages = SocialActivity::where('user_id', $user->id)->has("item")->count() / $count;
        $max_pages = ceil($max_pages);

        $pagination_result = [
            "max_pages" => $max_pages,
            "prev_page" => ($page > 1) ? $page - 1 : null,
            "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
        ];

        if ($page == 1) {
            $followed = Follower::where("user_id", auth()->user()->id)->where("follow_id", $user->id)->count() > 0;
            $collections = Collection::where('user_id', $user->id)->get();
            $total_books = 0;
            foreach ($collections as $c) {
                $total_books += $c->total_books;
            }
            $collection_ids = $collections->pluck("id");
            $oldest_item = Item::whereIn("collection_id", $collection_ids)
                ->orderBy("bought_on")->first();

            $now = Carbon::now("UTC");
            if ($oldest_item != null) {
                $old_item_date = Carbon::createFromFormat("Y-m-d", $oldest_item->bought_on, "UTC");
            } else {
                $old_item_date = Carbon::createFromFormat("Y-m-d H:i:s", $user->created_at, "UTC");
            }
            $days_diff = $old_item_date->diffInDays($now) + 1;

            return response()->json([
                "data" => [
                    "user" => UserResultResource::make($user),
                    "followed" => $followed,
                    "total_books" => $total_books,
                    "days_collecting" => $days_diff,
                    "activities" => SocialActivityResource::collection($activity),
                    "pagination" => $pagination_result
                ]
            ]);
        } else {
            return response()->json([
                "data" => [
                    "activities" => SocialActivityResource::collection($activity),
                    "pagination" => $pagination_result
                ]
            ]);
        }
    }

    public function getActivityFeed($page = 1, $count = 20){
        $user = Auth::user();
        $follower_id_array = $user->following->pluck("id");
        
        $activity_query = SocialActivity::whereIn("user_id", $follower_id_array)
            ->orderBy("created_at", "desc")->has("item");
        $activity = $activity_query->skip($count * ($page - 1))->take($count)->get();
        $max_pages = ceil(SocialActivity::whereIn("user_id", $follower_id_array)
            ->orderBy("created_at", "desc")->has("item")->count() / $count);

        $pagination_result = [
            "max_pages" => $max_pages,
            "prev_page" => ($page > 1) ? $page - 1 : null,
            "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
        ];

        return response()->json([
            "data" => [
                "activity" => SocialActivityResource::collection($activity),
                "pagination" => $pagination_result
            ]
        ]);
    }

    public function getGlobalFeed($page = 1, $count = 20){
        $activity = SocialActivity::orderBy("created_at", "desc")->has("item")
            ->skip($count * ($page - 1))->take($count)->get();
        $max_pages = ceil(SocialActivity::orderBy("created_at", "desc")->has("item")->count() / $count);

        $pagination_result = [
            "max_pages" => $max_pages,
            "prev_page" => ($page > 1) ? $page - 1 : null,
            "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
        ];

        return response()->json([
            "data" => [
                "activity" => SocialActivityResource::collection($activity),
                "pagination" => $pagination_result
            ]
        ]);
    }

    public function likeActivity(SocialActivity $activity){
        $like = new Like([
            "user_id" => Auth::user()->id,
        ]);
        $activity->likes()->save($like);
        return SocialActivityResource::make($activity);
    }

    public function unlikeActivity(SocialActivity $activity){
        $like = $activity->likes()->where("user_id", Auth::user()->id);
        $like->delete();
        return response()->json();
    }

    public function likes(SocialActivity $activity){
        return LikeResource::collection($activity->likes);
    }
}
