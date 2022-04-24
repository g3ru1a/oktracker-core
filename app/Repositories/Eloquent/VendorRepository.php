<?php

namespace App\Repositories\Eloquent;

use App\Models\BookVendor;
use App\Models\Item;
use App\Models\Report;
use App\Models\Role;
use App\Repositories\VendorRepositoryInterface;
use Illuminate\Support\Collection;

class VendorRepository extends BaseRepository implements VendorRepositoryInterface
{

    /**
     * VendorRepository constructor.
     *
     * @param BookVendor $model
     */
    public function __construct(BookVendor $model)
    {
        parent::__construct($model);
    }

    /**
     * @param User|null $user
     * @return Collection
     */
    public function all($user = null): Collection
    {
        if($user == null){
            $vendors = BookVendor::where('public', true)->whereNull('deleted_at')->get();
        }else{
            $vendors = BookVendor::where('public', true)->orWhere(function($query) use ($user) {
                $query->where('public', false)->where('user_id', $user->id);
            })->whereNull('deleted_at')->get();
        }
        return $vendors;
    }

    /**
     * @param User|null $user
     * @return Collection
     */
    public function getPrivate($user): Collection
    {
        $vendors = BookVendor::where(function ($query) use ($user) {
            $query->where('public', false)->where('user_id', $user->id);
        })->whereNull('deleted_at')->get();
        return $vendors;
    }

    /**
     * @param int $id
     * @param User|null $user
     * @return BookVendor|null
     */
    public function find($id, $user = null): BookVendor
    {
        if ($user == null) {
            $vendor = BookVendor::where('id', $id)->where('public', true)->first();
        } else {
            $vendor = BookVendor::where('id', $id)->where(function ($query) use ($user) {
                $query->where('public', false)->where('user_id', $user->id);
            })->orWhere('public', true)->get();
        }
        return $vendor;
    }

    /**
     * @param array $ids
     * @param User|null $user
     * @return Collection
     */
    public function findMany(array $ids, $user = null): Collection
    {
        if($user == null){
            $vendors = BookVendor::whereIn('id', $ids)->where('public', true)->get();
        }else{
            $vendors = BookVendor::whereIn('id', $ids)->where(function ($query) use ($user) {
                    $query->where('public', false)->where('user_id', $user->id);
                })->orWhere('public', true)->get();
        }
        return $vendors;
    }

    /**
     * @param array $attributes
     * @param int|null $suggested_by
     * @param bool $makeReport
     * @return BookVendor
     */
    public function create(array $attributes, $suggested_by = null, $makeReport = true): BookVendor
    {
        $vendor = BookVendor::create($attributes);
        if($makeReport){
            $r = new Report();
            $r->title = 'New Book Vendor: ' . $vendor->name;
            if ($suggested_by != null) $r->reporter_id = $suggested_by;
            $r->priority = Report::PRIORITY_POINTS["new_vendor"];
            $vendor->reports()->save($r);
        }
        return $vendor;
    }

    /**
     * @param BookVendor $vendor
     * @param array $attributes
     * @return BookVendor
     */
    public function update(BookVendor $vendor, array $attributes): BookVendor
    {
        $vendor->update($attributes);
        $vendor->save();
        $vendor->refresh();
        return $vendor;
    }

    /**
     * @param BookVendor $vendor
     * @return bool
     */
    public function delete(BookVendor $vendor): bool
    {
        Item::where('vendor_id', $vendor->id)->update(['vendor_id' => 1]);
        $deleted = $vendor->delete();
        return $deleted;
    }
}
