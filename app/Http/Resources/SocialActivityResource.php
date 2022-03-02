<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class SocialActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cover_url = $this->item->book->cover_url;
        if(!str_contains($cover_url, "http")){
            $cover_url = env("APP_URL").$cover_url;
        }

        if ($this->user->profile_photo_path != null) {
            $pfp_path = env("APP_URL") . $this->user->profile_photo_path;
        } else {
            $pfp_path = env("APP_URL") . "/missing_cover.png";
        }
        $likes = $this->likes()->count();
        $liked = $this->likes()->where("user_id", Auth::user()->id)->exists();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'username' => $this->user->name,
            'pfp_url' => $pfp_path,
            'created_at' => $this->created_at,
            // 'item' => ItemResource::make($this->item),
            'book_title' => $this->item->book->title,
            'book_cover_url' => $cover_url,
            'vendor_name' => ($this->item->vendor->id == 1) ? null : $this->item->vendor->name,
            'price' => $this->item->price,
            'currency' => $this->item->collection->currency,
            'likes' => $likes,
            'liked' => $liked,
        ];
    }
}
