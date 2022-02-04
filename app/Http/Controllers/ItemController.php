<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\BookVendor;
use App\Models\Collection;
use App\Models\Item;
use App\Models\SocialActivity;
use Config;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Item $item)
    {
        if ($item->collection->user->id == auth()->user()->id) {
            return ItemResource::make($item);
        } else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $collection = Collection::findOrFail($request->collection_id);
        if($collection->user_id != auth()->user()->id){
            return response()->json(['message' => 'Bad Request.'], 422);
        }
        $vendor = BookVendor::findOrFail($request->vendor_id);
        if($vendor->public == false && $vendor->user_id != auth()->user()->id){
            return response()->json(['message' => 'Bad Request.'], 422);
        }
        try {
            $item = Item::create($request->all());
            $collection = $item->collection;
            $collection->total_books += 1;
            $collection->total_cost += $item->price;
            $collection->save();

            $activity = new SocialActivity();
            $activity->user_id = auth()->user()->id;
            $activity->item_id = $item->id;
            $activity->action = Config::get('messages.activity.actions.new_item');
            $activity->save();

            return ItemResource::make($item);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong: ' . $th], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $item)
    {
        if ($item->collection->user->id == auth()->user()->id) {
            $collection = $item->collection;
            $collection->total_cost -= $item->price;
            $item->update($request->all());
            $item->refresh();
            $collection->total_cost += $item->price;
            $collection->save();
            return ItemResource::make($item);
        } else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if ($item->collection->user->id == auth()->user()->id) {
            $collection = $item->collection;
            $collection->total_cost -= $item->price;
            $collection->total_books -= 1;
            $collection->save();
            $item->delete();
            return ItemResource::make($item);
        } else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }
}
