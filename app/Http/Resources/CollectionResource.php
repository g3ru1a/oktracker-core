<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_books' => $this->total_books,
            'total_cost' => $this->total_cost,
            'currency' => $this->currency,
            'user' => UserResource::make($this->user),
            'deleted_at' => $this->deleted_at,
        ];
    }
}
