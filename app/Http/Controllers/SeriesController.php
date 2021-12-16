<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesStoreRequest;
use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Series::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeriesStoreRequest $request)
    {
        return Series::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Series::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SeriesStoreRequest $request, $id)
    {
        return Series::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Series::find($id)->delete();
    }

    /**
     * Search for series by title
     *
     * @param  string  $title
     * @return \Illuminate\Http\Response
     */
    public function search($title)
    {
        return Series::where('title', 'like', '%'. $title.'%')->get();
    }
}
