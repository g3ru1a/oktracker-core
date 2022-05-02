<?php

namespace App\Repositories\Eloquent;

use App\Models\Collection;
use App\Models\Item;
use App\Repositories\ItemRepositoryInterface;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{

    /**
     * UserRepository constructor.
     *
     * @param Item $model
     */
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    public function find($id): Item
    {
        return Item::findOrFail($id);
    }
    public function create(array $attributes): Item
    {
        $item = Item::create($attributes);
        $item->collection->addedItem($item);
        return $item;
    }

    public function update(Item $item, array $attributes): Item
    {
        $old_price = $item->price;
        $item->update($attributes);
        $item->collection->updatedItem($old_price, $item);
        return $item;
    }

    public function delete(Item $item): bool
    {
        $item->collection->removedItem($item);
        return $item->delete();
    }

    public function paginate(Collection $collection, $page, $count): object
    {
        $items = Item::where('collection_id', $collection->id)->skip($count * ($page - 1))->take($count)->get();
        $max_pages = Item::where('collection_id', $collection->id)->count() / $count;
        $max_pages = ceil($max_pages);

        $pagination_result = [
            "max_pages" => $max_pages,
            "prev_page" => ($page > 1) ? $page - 1 : null,
            "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
        ];

        return (object) ["items" => $items, "pagination" => $pagination_result];
    }

}
