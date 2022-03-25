<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\ItemResourceShort;
use App\Models\Collection;
use App\Models\Item;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{

    public function list()
    {
        return CollectionResource::collection(auth()->user()->collections);
    }


    public function items(Collection $collection, $page = 1, $count = 20){
        if ($collection->user->id == auth()->user()->id) {

            $items = Item::where('collection_id', $collection->id)->take($count)->get();
            $max_pages = Item::where('collection_id', $collection->id)->count() / $count;
            $max_pages = ceil($max_pages);

            $pagination_result = [
                "max_pages" => $max_pages,
                "prev_page" => ($page > 1) ? $page - 1 : null,
                "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
            ];

            return [
                "items" => ItemResourceShort::collection($items),
                ...$pagination_result
            ];
        } else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Collection $collection)
    {
        if($collection->user->id == auth()->user()->id){
            return CollectionResource::make($collection);
        }else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionRequest $request)
    {
        $user = Auth::user();
        if ($user->role_id === Role::USER && count($user->collections) >= 5) {
            return response()->json(["message" => "Collection Limit Reached"]);
        }
        try {
            $collection = Collection::create($request->all());
            $user->collections()->save($collection);
            $collection->refresh();
            return CollectionResource::make($collection);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong: '.$th], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionRequest $request, Collection $collection)
    {
        if ($collection->user->id == auth()->user()->id) {
            $collection->update($request->all());
            $collection->refresh();
            return CollectionResource::make($collection);
        } else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        if ($collection->user->id == auth()->user()->id) {
            $collection->delete();
            return CollectionResource::make($collection);
        } else return response()->json(['message' => 'Cannot access this resource.'], 401);
    }
}
