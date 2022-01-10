<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResourceShort extends JsonResource
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
            'book_id' => $this->book_id,
            'collection_id' => $this->collection_id,
            'vendor_id' => $this->vendor_id,
            'price' => $this->price,
            'bought_on' => $this->bought_on,
            'arrived' => $this->arrived,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
