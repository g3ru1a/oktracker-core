<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Series;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ISBNLookUpController extends Controller
{

    function lookup($isbn){
        $isbn = preg_replace('/\D+/', '', $isbn);
        if(strlen($isbn) == 13 && (substr($isbn, 0, 3) != "978" && substr($isbn, 0, 3) != "979")) {
            return response()->json('Wrong ISBN Format', 422);
        }
        switch(strlen($isbn)){
            case 10: $book = self::lookupISBN10($isbn);break;
            case 13: $book = self::lookupISBN13($isbn);break;
            default:
                return response()->json('Wrong ISBN Format', 422);
        }
        if($book == false){
            $data = self::findBookData($isbn);
            if($data != false) {
                if (strlen($isbn) == 13) $data["isbn_13"] = $isbn;
                if (strlen($isbn) == 10) $data["isbn_10"] = $isbn;
                $book = self::makeBookFromData($data);
            }else{
                $book = self::makeMissingBookReport($isbn);
                return BookResource::make($book);
            }
        }
        return BookResource::make($book);
    }

    private static function makeMissingBookReport($isbn){
        $book = new Book();
        $book->title = "Unknown";
        if(strlen($isbn) == 13) $book->isbn_13 = $isbn;
        else $book->isbn_10 = $isbn;
        $book->save();

        $r = new Report();
        $r->title = "Collect book info for isbn: " . $isbn;
        $r->priority = 50;
        $book->reports()->save($r);
        return $book;
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

    private static function makeBookFromData($data){
        $series = Series::where('title', 'like', '%' . $data[ "clean_title"] . '%')->first();

        if ($series == null) {
            $series = Series::create([
                'title' => $data["clean_title"],
                'language' => json_encode([$data["language"] ?? ""]),
                'publisher' => json_encode([$data["publisher"] ?? ""]),
                'summary' => $data["synopsis"],
                'cover_url' => $data["cover_url"],
                'authors' => isset($data["authors"]) ? $data["authors"] : null,
            ]);
            $pp = Report::calculateSeriesPriorityPoints($series);
            $r = new Report();
            $r->title = 'New Series: ' . $series->title;
            $r->priority = $pp;
            $series->reports()->save($r);
        }

        $book = Book::create($data);

        $series->books()->save($book);
        $book->refresh();
        $pp = Report::calculateBookPriorityPoints($book);
        $r = new Report();
        $r->title = 'New Book: ' . $book->title;
        $r->priority = $pp;
        $book->reports()->save($r);
        return Book::with('series')->find($book->id);
    }

    private static function findBookData($isbn){
        $data = self::lookupISBNAPI($isbn);

        $pull_cover = $data != false && $data["cover_url"] == null;
        $data_sc = self::scrapeAmazonISBN($isbn, $pull_cover);

        if($data == false && $data_sc == false) return false;

        if($data != false) {
            $keys = array_keys($data_sc);
            foreach ($keys as $key) {
                if ($data[$key] == null && $data_sc[$key] != null) $data[$key] = $data_sc[$key];
            }

            if ($data_sc["cover_url"] != null) {
                $data["cover_url"] = $data_sc["cover_url"];
            }
        }else $data = $data_sc;

        $data["cover_url"] = self::processCover($data["cover_url"]);
        return $data;
    }

    private static function scrapeAmazonISBN($isbn, $pull_cover = false){
        $process = new Process(['python3', env("SCRAPER_PATH"), $isbn]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $data_sc = json_decode($process->getOutput());
        if($data_sc == "[]") return false;
        $clean_title = self::RemoveExtrasFromTitle($data_sc->title);
        $volume_number = self::GetVolumeNumber($data_sc->title);
        $data_sc->language = ($data_sc->language == null) ? "Unknown" : $data_sc->language;

        return [
            'isbn_10' => preg_replace('/\D+/', '', $data_sc->isbn_10),
            'isbn_13' => preg_replace('/\D+/', '', $data_sc->isbn_13),
            'title' => $data_sc->title,
            'clean_title' => $clean_title,
            'pages' => $data_sc->pages ?? null,
            'synopsis' => $data_sc->synopsis,
            'volume_number' => $volume_number,
            'publish_date' => null,
            'binding' => $data_sc->binding ?? "paperback",
            'authors' => json_encode($data_sc->authors) ?? null,
            'publisher' => $data_sc->publisher ?? null,
            'language' => $data_sc->language,
            'cover_url' => $data_sc->cover_url,
        ];
    }

    /**
     * @return Object|boolean
     */
    private static function lookupISBNAPI($isbn){
        $response = Http::withHeaders([
            'Authorization' => env('ISBN_API')
        ])->accept('application/json')->get('https://api2.isbndb.com/book/' . $isbn);
        if($response->failed()){
            return false;
        }
        $data = json_decode($response->getBody());
        $clean_title = self::RemoveExtrasFromTitle($data->book->title_long);
        $volume_number = self::GetVolumeNumber($data->book->title_long);

        if(isset($data->book->overview)){
            $synopsis = htmlspecialchars($data->book->overview);
        }else if(isset($data->book->synopsys)){
            $synopsis = htmlspecialchars($data->book->synopsys);
        }else $synopsis = null;

        if(isset($data->book->language)){
            $language = self::ParseLanguage($data->book->language);
        }else $language = "Unknown";

        if(isset($data->book->date_published)){
            $date_pub = new \DateTime($data->book->date_published);
            $date_pub = $date_pub->format("Y-m-d");
        }else $date_pub = null;
        
        return [
            'isbn_10' => preg_replace('/\D+/', '', $data->book->isbn),
            'isbn_13' => preg_replace('/\D+/', '', $data->book->isbn13),
            'title' => $data->book->title_long,
            'clean_title' => $clean_title,
            'pages' => $data->book->pages ?? null,
            'synopsis' => $synopsis,
            'volume_number' => $volume_number,
            'publish_date' => $date_pub,
            'binding' => $data->book->binding ?? "paperback",
            'authors' => json_encode($data->book->authors) ?? null,
            'publisher' => $data->book->publisher ?? null,
            'language' => $language,
            'cover_url' => $data->book->image,
        ];
    }

    private static function processCover($cover_url){
        if($cover_url == null) return '/missing_cover.png';
        $filename = self::generateRandomString().basename($cover_url);

        $path = "/images_from_api/" . $filename;

        $img = Image::make($cover_url)->encode('jpg', 75)->stream('jpg', 90);

        Storage::disk('public')->put($path, $img);

        return '/storage'.$path;
    }

    private static function RemoveExtrasFromTitle($title){
        $ct = preg_replace('/[（]+/', "(", $title); //normalize brackets
        $ct = preg_replace('/[）]+/', ")", $ct); //normalize brackets
        $ct = preg_replace('/(\s?\(.[^()]+\))+|(\s?\([0-9]+\))+/', "", $ct); //clear brackets
        $ct = preg_replace('/(\s?\(.[^()]+\))+|(\s?\([0-9]+\))+/', "", $ct); //clear brackets parse 2
        $ct = preg_replace('/(-?,?\s?[Tt]ome\s?\d+)|(,?\s?[Vv]ol\.\s?\d+)|(,?\s?[Vv]olume\s?\d+)|([\s,:-]\d{1,3}($|[^0-9\(\),]))/',
            "", $ct); //clear volume number
        $ct = preg_replace('/(\s(\d+)\s?:)|(,?\s?[Tt]ome\s?\d+\s?:)/',
            ":", $ct); //clear volume nr and leave :
        $ct = preg_replace(
            '/([ \t]+$)|(^\s+)/',
            "", $ct); // clear whitespace trailing and leading
        
        return $ct;
    }

    private static function GetVolumeNumber($title){
        preg_match('/(\d{1,3})(?!.*\d)/', $title, $matches);
        return count($matches) > 0 ? $matches[0] : null;
    }

    private static function ParseLanguage($lang){
        $lang_code = explode("_", $lang)[0];
        return $lang_code;
    }

    private static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}



//9784040646176

//9781421526690

