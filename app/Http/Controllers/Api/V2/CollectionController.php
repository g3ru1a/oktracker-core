<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionRequest;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\ItemResourceShort;
use App\Models\Collection;
use App\Models\Item;
use App\Models\Role;
use App\Repositories\CollectionRepositoryInterface;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    private CollectionRepositoryInterface $collectionRepository;
    private ItemRepositoryInterface $itemRepository;

    public function __construct(CollectionRepositoryInterface $collectionRepository, ItemRepositoryInterface $itemRepository)
    {
        $this->collectionRepository = $collectionRepository;
        $this->itemRepository = $itemRepository;
    }

    public function all()
    {
        return CollectionResource::collection($this->collectionRepository->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Collection $collection)
    {
        $this->authorize('view', $collection);
        return CollectionResource::make($collection);
    }

    /**
     * @param Collection $collection
     * @param int $page
     * @param int $count
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function items(Collection $collection, $page = 1, $count = 500)
    {
        $this->authorize('use', $collection);

        $data = $this->itemRepository->paginate($collection, $page, $count);

        return response()->json([
            "items" => ItemResourceShort::collection($data->items),
            "pagination" => $data->pagination,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CollectionRequest $request)
    {
        $this->authorize('create', Collection::class);
        $collection = $this->collectionRepository->create($request->all());
        return CollectionResource::make($collection);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CollectionRequest $request, Collection $collection)
    {
        $this->authorize('update', $collection);
        $collection = $this->collectionRepository->update($collection, $request->all());
        return CollectionResource::make($collection);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Collection $collection)
    {
        $this->authorize('destroy', $collection);
        $deleted = $this->collectionRepository->delete($collection);
        if($deleted){
            return CollectionResource::make($collection);
        }else{
            return response()->json([], 500);
        }
    }
}
