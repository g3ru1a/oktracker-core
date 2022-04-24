<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookVendorRequest;
use App\Http\Resources\BookVendorResource;
use App\Models\BookVendor;
use App\Models\Item;
use App\Models\Report;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Get a bulk of books information, max 100 at once.
     */
    public function findBulk(Request $request)
    {
        if (!isset($request->vendor_ids)) return response()->json(["Missing Vendor IDs"], 422);
        $vendor_ids = json_decode($request->vendor_ids);
        if (count($vendor_ids) > 100) return response()->json(["Too Many IDs"], 422);
        $vendor_ids = array_unique($vendor_ids);
        $vendors = BookVendor::findMany($vendor_ids);
        return response()->json([
            "data" => $vendors
        ], 200);
    }

    public function getAll()
    {
        return response()->json(BookVendor::where('public', true)->get(), 200);
    }

    public function suggest(BookVendorRequest $request)
    {
        $request->public = false;
        $bookvendor = BookVendor::create($request->all());
        $r = new Report();
        $r->title = 'New Book Vendor: ' . $bookvendor->name;
        $r->reporter_id = auth()->user()->id;
        $r->priority = Report::PRIORITY_POINTS["new_vendor"];
        $bookvendor->reports()->save($r);
        return response()->json(['message' => 'Book Vendor Request successfull'], 201);
    }

    public function createPrivate(BookVendorRequest $request)
    {
        $bookvendor = BookVendor::create($request->all());
        $bookvendor->user_id = auth()->user()->id;
        $bookvendor->public = false;
        $bookvendor->save();
        $bookvendor->refresh();
        return BookVendorResource::make($bookvendor);
    }

    public function updatePrivate(BookVendorRequest $request, BookVendor $vendor)
    {
        if ($vendor->user_id == null || $vendor->user_id != auth()->user()->id)
            return response()->json("No Access", 403);

        $vendor->update($request->all());
        $vendor->user_id = auth()->user()->id;
        $vendor->public = false;
        $vendor->save();
        $vendor->refresh();
        return BookVendorResource::make($vendor);
    }

    public function deletePrivate(Request $request, BookVendor $vendor)
    {
        if ($vendor->user_id == null || $vendor->user_id != auth()->user()->id)
            return response()->json("No Access", 403);
        Item::where('vendor_id', $vendor->id)->update(['vendor_id' => 1]);
        $vendor->delete();
        $vendor->refresh();
        return BookVendorResource::make($vendor);
    }

    public function getPrivate()
    {
        $user = auth()->user();
        $vendors = BookVendor::where('user_id', $user->id)->whereNull('deleted_at')->get();
        return BookVendorResource::collection($vendors);
    }
}
