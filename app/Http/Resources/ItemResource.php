<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'book' => BookResource::make($this->book),
            'collection' => CollectionResource::make($this->collection),
            'vendor' => BookVendorResource::make($this->vendor),
            'price' => $this->price,
            'bought_on' => $this->bought_on,
            'arrived' => $this->arrived,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
