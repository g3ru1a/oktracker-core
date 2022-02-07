<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookVendorRequest;
use App\Http\Resources\BookVendorResource;
use App\Models\BookVendor;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class BookVendorController extends Controller
{

    //**API FUNCTIONS */

    public function getAll(){
        return response()->json(BookVendor::where('public', true)->get(), 200);
    }

    public function suggest(BookVendorRequest $request){
        $request->public = false;
        $bookvendor = BookVendor::create($request->all());
        $r = new Report();
        $r->title = 'Validate new Book Vendor: ' . $bookvendor->name;
        $r->details = 'Submitted by '.auth()->user()->name;
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
        if($vendor->user_id == null || $vendor->user_id != auth()->user()->id) 
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
        $vendor->delete();
        $vendor->refresh();
        return BookVendorResource::make($vendor);
    }

    public function getPrivate(){
        $user = auth()->user();
        $vendors = BookVendor::where('user_id', $user->id)->whereNull('deleted_at')->get();
        return BookVendorResource::collection($vendors);
    }

    //**w/API FUNCTIONS */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view_list', BookVendor::class);

        return view('pages.bookvendors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('view_create', BookVendor::class);

        return view('pages.bookvendors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookVendorRequest $request)
    {
        $this->authorize('create', BookVendor::class);
        $bookvendor = BookVendor::create($request->all());
        $bookvendor->public = $request->is_public == 'on';
        $bookvendor->save();
        if ($request->hasFile('logo')) {
            $originalExtension = $request->file('logo')->getClientOriginalExtension();
            $filename = 'logo' . $originalExtension;
            $path = $request->file('logo')->storeAs('public/vendors/' . $bookvendor->id, $filename);
            $bookvendor->path_to_logo = '/' . str_replace('public', 'storage', $path);
            $bookvendor->save();
        }
        return redirect(route('bookvendors.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  BookVendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(BookVendor $bookvendor)
    {
        $this->authorize('view_edit', BookVendor::class);

        return view('pages.bookvendors.edit', [
            'vendor' => $bookvendor
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookVendor $bookvendor)
    {
        $this->authorize('update', BookVendor::class);
        $bookvendor->update($request->all());
        $bookvendor->public = $request->is_public == 'on';
        $bookvendor->save();

        if ($request->hasFile('logo')) {
            $originalExtension = $request->file('logo')->getClientOriginalExtension();

            if (file_exists(public_path() . 'vendors/' . $bookvendor->id . '/logo' . $originalExtension)) {
                unlink(public_path() . 'vendors/' . $bookvendor->id . '/logo' . $originalExtension);
            }

            $filename = 'logo' . $originalExtension;
            $path = $request->file('logo')->storeAs('public/vendors/' . $bookvendor->id, $filename);
            $bookvendor->path_to_logo = '/' . str_replace('public', 'storage', $path);
            $bookvendor->save();
        }

        return redirect(route('bookvendors.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookVendor $bookvendor)
    {
        $this->authorize('destroy', BookVendor::class);

        $bookvendor->delete();
        return redirect(route('bookvendors.index'));
    }
}
