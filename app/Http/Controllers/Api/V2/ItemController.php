<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\BookVendor;
use App\Models\Collection;
use App\Models\Item;
use App\Models\SocialActivity;
use App\Repositories\ItemRepositoryInterface;
use Config;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    private ItemRepositoryInterface $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Item $item)
    {
        $this->authorize('access', $item);
        return ItemResource::make($item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ItemRequest $request)
    {
        $collection = Collection::findOrFail($request->collection_id);
        $vendor = BookVendor::findOrFail($request->vendor_id);
        $this->authorize('use', $collection);
        $this->authorize('use', $vendor);
        
        $item = $this->itemRepository->create($request->all());

        //TODO: Reimplement Social Activity notification
        /** OLD IMPLEMENTATION
        $activity = new SocialActivity();
        $activity->user_id = auth()->user()->id;
        $activity->item_id = $item->id;
        $activity->action = Config::get('messages.activity.actions.new_item');
        $activity->save();
         */

        return ItemResource::make($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $this->authorize('access', $item);

        $item = $this->itemRepository->update($item, $request->all());
        return ItemResource::make($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $item)
    {
        $this->authorize('access', $item);

        //TODO: Reimplement Social Activity notification deletion
        /** OLD IMPLEMENTATION
        $activity = SocialActivity::where('user_id', auth()->user()->id)
            ->where('item_id', $item->id)->first();
        $activity->delete();
        */

        $deleted = $this->itemRepository->delete($item);
        if($deleted) return ItemResource::make($item->refresh());
        else return response()->json([], 500);
    }
}
