<?php

namespace App\Repositories;

use App\Models\Collection;
use Illuminate\Support\Collection as SCollection;

interface CollectionRepositoryInterface
{
    public function all(): SCollection;

    public function create(array $attributes): Collection;
    public function update(Collection $collection, array $attributes): Collection;
    public function delete(Collection $collection): bool;
}