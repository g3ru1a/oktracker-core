<?php

namespace App\Console\Commands;

use App\Http\Controllers\ISBNLookUpController;
use App\Models\Book;
use App\Models\BookVendor;
use App\Models\Series;
use Illuminate\Console\Command;

class S3MoveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's3:move';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload all locally/remote api stored images to s3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $books = Book::where('cover_url', 'not like', "%amazonaws%")->get();

        foreach($books as $book){
            $url = (!str_contains($book->cover_url, "http") ? env('APP_URL').$book->cover_url : $book->cover_url);
            $this->info("From: ".$url);
            $book->cover_url = ISBNLookUpController::processCover($url, 'books/'.$book->id);
            $book->save();
            $this->info("Moved To: ".$book->cover_url);
        }

        $series = Series::where('cover_url', 'not like', "%amazonaws%")->get();

        foreach($series as $serie){
            $url = (!str_contains($serie->cover_url, "http") ? env('APP_URL').$serie->cover_url : $serie->cover_url);
            $this->info("From: ".$url);
            $serie->cover_url = ISBNLookUpController::processCover($url, 'series/'.$serie->id);
            $serie->save();
            $this->info("Moved To: ".$serie->cover_url);
        }

        $bookvendors = BookVendor::where('path_to_logo', 'not like', "%amazonaws%")->get();

        foreach($bookvendors as $bv){
            $url = (!str_contains($bv->path_to_logo, "http") ? env('APP_URL').$bv->path_to_logo : $bv->path_to_logo);
            $this->info("From: ".$url);
            $bv->path_to_logo = ISBNLookUpController::processCover($url, 'bookvendors/'.$bv->id);
            $bv->save();
            $this->info("Moved To: ".$bv->path_to_logo);
        }
        return 0;
    }
}
