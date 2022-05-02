<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\Item;

interface ItemRepositoryInterface
{
    public function find($id): Item;
    public function create(array $attributes): Item;
    public function update(Item $item, array $attributes): Item;
    public function delete(Item $item): bool;
    public function paginate(Collection $collection, $page, $count): object;
}
