<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Series;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Http;

class ISBNLookUpController extends Controller
{
    function lookup($isbn){
        $isbn = preg_replace('/[^0-9.]+/', '', $isbn);
        switch(strlen($isbn)){
            case 10: $book = self::lookupISBN10($isbn);break;
            case 13: $book = self::lookupISBN13($isbn);break;
            default:
                return response()->json('Wrong ISBN Format', 422);
        }
        if($book == false){
            $book = self::lookupISBNAPI($isbn);
        }
        if($book == false){
            self::makeMissingBookReport($isbn);
            return response()->json('No book found.', 404);
        }else return BookResource::make($book);
    }

    private static function makeMissingBookReport($isbn){
        $book = new Book();
        $book->title = "Unknown";
        if(strlen($isbn) == 13) $book->isbn_13 = $isbn;
        else $book->isbn_10 = $isbn;
        $book->save();

        $r = new Report();
        $r->title = "Collect book info for isbn: " . $isbn;
        $book->reports()->save($r);
    }

    /**
     * @return Book|boolean
     */
    private static function lookupISBN10($isbn10){
        $book = Book::with('series')->where('isbn_10', $isbn10)->first();
        if($book){
            return $book;
        }
        return false;
    }

    /**
     * @return Book|boolean
     */
    private static function lookupISBN13($isbn13)
    {
        $book = Book::with('series')->where('isbn_13', $isbn13)->first();
        if ($book) {
            return $book;
        }
        return false;
    }

    /**
     * @return Book|boolean
     */
    private static function lookupISBNAPI($isbn){
        $response = Http::withHeaders([
            'Authorization' => env('ISBN_API')
        ])->accept('application/json')->get('https://api2.isbndb.com/book/' . $isbn);
        if($response->failed()){
            return false;
        }
        $data = json_decode($response->getBody());
        $title = explode(',', $data->book->title_long);
        $series = Series::where('title', 'like', '%'.$title[0].'%')->first();
        if($series == null){
            $series = Series::create([
                'title' => $data->book->title_long,
                'language' => $data->book->language,
                'authors' => isset($data->book->authors) ? json_encode($data->book->authors) : null,
            ]);
            $r = new Report();
            $r->title = 'Validate new series: '.$series->title;
            $series->reports()->save($r);
        }
        $book = Book::create([
            'isbn_10' => $data->book->isbn,
            'isbn_13' => $data->book->isbn13,
            'title' => $data->book->title_long,
            'pages' => $data->book->pages ?? null,
            'publish_date' => $data->book->date_published ?? null,
            'cover_url' => $data->book->image ?? '/missing_cover.png',
        ]);
        $series->books()->save($book);
        $r = new Report();
        $r->title = 'Validate new book: ' . $book->title;
        $book->reports()->save($r);
        return Book::with('series')->find($book->id);
    }
}
//9784040646176

//9781421526690