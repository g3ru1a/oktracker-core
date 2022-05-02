<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookVendorRequest;
use App\Http\Requests\VendorBulkRequest;
use App\Http\Resources\BookVendorResource;
use App\Models\BookVendor;
use App\Models\User;
use App\Repositories\VendorRepositoryInterface;
use Illuminate\Http\Request;
use Auth;
use Response;

class VendorController extends Controller
{
    private VendorRepositoryInterface $vendorRepository;

    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    public function all()
    {
        return BookVendorResource::collection($this->vendorRepository->all());
    }

    /**
     * Get a bulk of books information, max 100 at once.
     */
    public function bulk(VendorBulkRequest $request)
    {
        $vendor_ids = htmlspecialchars($request->vendor_ids);
        $vendor_ids = json_decode($vendor_ids);
        if (count($vendor_ids) > 100) return response()->json(["Too Many IDs"], 422);

        $vendor_ids = array_unique($vendor_ids);

        $vendors = $this->vendorRepository->findMany($vendor_ids, Auth::user());
        return BookVendorResource::collection($vendors);
    }

    public function suggest(BookVendorRequest $request)
    {
        $request->public = false;
        $this->vendorRepository->create($request->all(), Auth::user()->id);
        return response()->json(['message' => 'Book Vendor Request successfull'], 201);
    }

    public function showPrivateVendors()
    {
        $vendors = $this->vendorRepository->getPrivate(Auth::user());
        return BookVendorResource::collection($vendors);
    }

    public function createPrivateVendor(BookVendorRequest $request)
    {
        $attrs = [
            'name' => $request->name,
            'user_id' => Auth::user()->id,
            'public' => false
        ];
        $bookvendor = $this->vendorRepository->create($attrs, null, false);
        return BookVendorResource::make($bookvendor);
    }

    public function updatePrivateVendor(BookVendorRequest $request, BookVendor $vendor)
    {
        $this->authorize('api_update', $vendor);

        $attrs = [
            'name' => $request->name,
            'user_id' => Auth::user()->id,
            'public' => false
        ];
        $vendor = $this->vendorRepository->update($vendor, $attrs);
        return BookVendorResource::make($vendor);
    }

    public function destroyPrivateVendor(Request $request, BookVendor $vendor)
    {
        $this->authorize('api_destroy', $vendor);
        $deleted = $this->vendorRepository->delete($vendor);
        if(!$deleted) return response()->json([], 500);
        return BookVendorResource::make($vendor->refresh());
    }
}
