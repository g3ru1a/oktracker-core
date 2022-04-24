<?php

namespace App\Repositories;

use App\Models\BookVendor;
use App\Models\User;
use Illuminate\Support\Collection;

interface VendorRepositoryInterface
{
    public function all($user = null): Collection;
    public function findMany(array $ids, $user = null): Collection;
    public function getPrivate($user): Collection;

    public function create(array $attributes, $suggested_by = null, $makeReport = true): BookVendor;
    public function update(BookVendor $vendor, array $attributes): BookVendor;
    public function delete(BookVendor $vendor): bool;
}
