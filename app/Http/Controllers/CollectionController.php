<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\ItemResourceShort;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{

    public function list()
    {
        return CollectionResource::collection(auth()->user()->collections);
    }


    public function items(Collection $collection){
        if ($collection->user->id == auth()->user()->id) {
            return ItemResourceShort::collection($collection->items);
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
        try {
            $collection = Collection::create($request->all());
            Auth::user()->collections()->save($collection);
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
