<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Book $book)
    {
        return BookResource::make($book);
    }

    /**
     * Get a bulk of books information, max 100 at once.
     */
    public function bulk($book_ids)
    {
        if(!is_array($book_ids)) return response()->json(["IDs bad format."], 422);
        if (count($book_ids) > 100) return response()->json(["Too Many IDs"], 422);

        $book_ids = array_unique($book_ids);
        $books = Book::findMany($book_ids);
        return BookResource::collection($books);
    }
}
