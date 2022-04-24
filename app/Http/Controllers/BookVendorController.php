<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookVendorRequest;
use App\Http\Resources\BookVendorResource;
use App\Models\BookVendor;
use App\Models\Report;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;

class BookVendorController extends Controller
{
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
            $bookvendor->path_to_logo = ISBNLookUpController::processCover($request->file('logo'), 'bookvendor/'.$bookvendor->id, true);
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
            $bookvendor->path_to_logo = ISBNLookUpController::processCover($request->file('logo'), 'bookvendor/'.$bookvendor->id, true);
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
