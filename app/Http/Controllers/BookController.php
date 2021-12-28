<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use App\Models\Series;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view_list', Book::class);

        return view('pages.books.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('view_create', Book::class);

        $series = Series::all();
        return view('pages.books.create', [
            'series' => $series
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookStoreRequest $request)
    {
        $this->authorize('create', Book::class);

        $book = Book::create($request->all());
        $book->oneshot = $request->is_oneshot == 'on';
        $book->save();
        if ($request->hasFile('cover')) {
            $originalExtension = $request->file('cover')->getClientOriginalExtension();
            $filename = 'cover'. $originalExtension;
            $path = $request->file('cover')->storeAs('public/books/'.$book->id, $filename);
            $book->cover_url = '/' . str_replace('public', 'storage', $path);
            $book->save();
        }
        return redirect(route('book.index'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $this->authorize('view_edit', Book::class);

        $series = Series::all();
        return view('pages.books.edit', [
            'book' => $book,
            'series' => $series
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookStoreRequest $request, Book $book)
    {
        $this->authorize('update', Book::class);

        $book->update($request->all());
        $book->oneshot = $request->is_oneshot == 'on';
        $book->save();

        if ($request->hasFile('cover')) {
            $originalExtension = $request->file('cover')->getClientOriginalExtension();

            if (file_exists(public_path() . 'books/' . $book->id . '/cover' . $originalExtension)) {
                unlink(public_path() . 'books/' . $book->id . '/cover' . $originalExtension);
            }

            $filename = 'cover' . $originalExtension;
            $path = $request->file('cover')->storeAs('public/books/' . $book->id, $filename);
            $book->cover_url = '/' . str_replace('public', 'storage', $path);
            $book->save();
        }
        return redirect(route('book.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $this->authorize('destroy', Book::class);

        $book->delete();
        return redirect(route('book.index'));
    }
}
