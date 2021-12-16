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
        $series = Series::all()->take(15);
        return view('pages.series.index', [
            'series' => $series
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.series.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeriesStoreRequest $request)
    {
        $series = Series::create($request->all());
        if($request->hasFile('cover')){
            $originalExtension = $request->file('cover')->getClientOriginalExtension();
            $filename = bin2hex(random_bytes(20)).$originalExtension;
            $path = $request->file('cover')->storeAs('public/series/covers', $filename);
            $series->cover_url = '/'.$path;
            $series->save();
        }
        return redirect(route('series.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Series $series
     * @return \Illuminate\Http\Response
     */
    public function show(Series $series)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Series $series
     * @return \Illuminate\Http\Response
     */
    public function edit(Series $series)
    {
        return view('pages.series.edit', [
            'series' => $series
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Series $series
     * @return \Illuminate\Http\Response
     */
    public function update(SeriesStoreRequest $request, Series $series)
    {
        $series->update($request->all());
        if ($request->hasFile('cover')) {
            $originalExtension = $request->file('cover')->getClientOriginalExtension();
            $filename = bin2hex(random_bytes(20)) . $originalExtension;
            $path = $request->file('cover')->storeAs('public/series/covers', $filename);
            $series->cover_url = '/' . str_replace('public', 'storage', $path);
            $series->save();
        }
        return redirect(route('series.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Series $series)
    {
        $series->delete();
        return redirect(route('series.index'));
    }
}
