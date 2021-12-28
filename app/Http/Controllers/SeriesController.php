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
        $this->authorize('view_list', Series::class);

        return view('pages.series.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('view_create', Series::class);
        
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
        $this->authorize('create', Series::class);
        
        $series = Series::create($request->all());
        if($request->hasFile('cover')){
            $originalExtension = $request->file('cover')->getClientOriginalExtension();
            $filename = 'cover'.$originalExtension;
            $path = $request->file('cover')->storeAs('public/series/'.$series->id, $filename);
            $series->cover_url = '/' . str_replace('public', 'storage', $path);
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
        $this->authorize('view_edit', Series::class);
        
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
        $this->authorize('update', Series::class);
        
        $series->update($request->all());
        if ($request->hasFile('cover')) {
            $originalExtension = $request->file('cover')->getClientOriginalExtension();

            if (file_exists(public_path() . 'series/' . $series->id . '/cover'. $originalExtension)){
                unlink(public_path() . 'series/' . $series->id . '/cover' . $originalExtension);
            }

            $filename = 'cover' . $originalExtension;
            $path = $request->file('cover')->storeAs('public/series/'.$series->id, $filename);
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
        $this->authorize('destroy', Series::class);
        
        $series->delete();
        return redirect(route('series.index'));
    }
}
