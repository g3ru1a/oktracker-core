<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->profile_photo_path != null) {
            $pfp_path = env("APP_URL") . $this->profile_photo_path;
        }else {
            $pfp_path = env("APP_URL") . "/missing_cover.png";
        }
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "pfp" => $pfp_path,
            'role' => RoleResource::make($this->role),
            'badges' => $this->badges
        ];
    }
}
