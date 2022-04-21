<?php

namespace App\Repositories\Eloquent;

use App\Models\Collection;
use Illuminate\Support\Collection as SCollection;
use App\Repositories\CollectionRepositoryInterface;
use Auth;

class CollectionRepository extends BaseRepository implements CollectionRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param Collection $model
     */
    public function __construct(Collection $model)
    {
        parent::__construct($model);
    }

    /**
     * @return SCollection
     */
    public function all(): SCollection
    {
        return Auth::user()->collections;
    }

    /**
     * @param array $attributes
     * @return Collection
     */
    public function create(array $attributes): Collection
    {
        $user = Auth::user();
        $collection = Collection::create($attributes);
        $user->collections()->save($collection);
        $collection->refresh();
        return $collection;
    }

    /**
     * @param Collection $collection
     * @param array $attributes
     * @return Collection
     */
    public function update(Collection $collection, array $attributes): Collection
    {
        $collection->update($attributes);
        $collection->refresh();
        return $collection;
    }

    /**
     * @param Collection $collection
     * @return bool
     */
    public function delete(Collection $collection): bool
    {
        return $collection->delete();
    }

}