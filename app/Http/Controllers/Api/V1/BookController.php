<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Get a bulk of books information, max 100 at once.
     */
    public function findBulk(Request $request)
    {
        if (!isset($request->book_ids)) return response()->json(["Missing Book IDs"], 422);
        $book_ids = json_decode($request->book_ids);
        if (count($book_ids) > 100) return response()->json(["Too Many IDs"], 422);
        $book_ids = array_unique($book_ids);
        $books = Book::findMany($book_ids);
        return BookResource::collection($books);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Book $book)
    {
        return BookResource::make($book);
    }
}
