<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
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
        try {
            $item = Item::create($request->all());
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
            $item->update($request->all());
            $item->refresh();
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
            $item->delete();
            return ItemResource::make($item);
        } else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }
}
