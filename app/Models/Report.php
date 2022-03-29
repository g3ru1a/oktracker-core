<?php

namespace App\Models;

use App\Models\Report as ModelsReport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'details', 'status', 'priority', 'assignee_id',
     'reporter_id', 'item_id', 'item_type'];

    public const TYPE = [
        "ANY" => 1,
        "COLLECT" => 2,
        "USER_REPORT" => 3,
        "IMPORTANT" => 4,
        "MINOR" => 5,
        "CLEAN" => 6,
        "SEVERE" => 7
    ];
    
    public const STATUS_CREATED = 0;
    public const STATUS_ASSIGNED = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_DROPPED = 3;

    public const PRIORITY_POINTS = [
        "title" => 5,
        "cover" => 5,
        "synopsis" => 3,
        "publisher" => 1,
        "language" => 2,
        "authors" => 1,
        "publish_date" => 1,
        "pages" => 1,
        "volume_number" => 1,
        "series_id" => 10,
        "binding" => 1,
        "new_vendor" => 5,
    ];

    public const PRIORITY_TRESHOLDS = [
        "AWAITING_REVIEW" => 0,
        "MINOR_ISSUES" => 1,
        "IMPORTANT_ISSUES" => 5,
        "SEVERE_ISSUES" => 10,
        "COLLECT_DATA" => 50,
    ];

    public function item()
    {
        return $this->morphTo();
    }

    public function assignee(){
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public static function calculateBookPriorityPoints(Book $book){
        $pp = 0;
        if ($book->clean_title == null) $pp += Report::PRIORITY_POINTS["title"];
        if ($book->cover == "/missing_cover.png") $pp += Report::PRIORITY_POINTS["cover"];
        if ($book->synopsis == null) $pp += Report::PRIORITY_POINTS["synopsis"];
        if ($book->publisher == null) $pp += Report::PRIORITY_POINTS["publisher"];
        if ($book->language == null) $pp += Report::PRIORITY_POINTS["language"];
        if ($book->authors == null) $pp += Report::PRIORITY_POINTS["authors"];
        if ($book->publish_date == null) $pp += Report::PRIORITY_POINTS["publish_date"];
        if ($book->pages == null) $pp += Report::PRIORITY_POINTS["pages"];
        if ($book->volume_number == null) $pp += Report::PRIORITY_POINTS["volume_number"];
        if ($book->series_id == null) $pp += Report::PRIORITY_POINTS["series_id"];
        return $pp;
    }

    public static function calculateSeriesPriorityPoints(Series $series)
    {
        $pp = 0;
        if ($series->title == null) $pp += Report::PRIORITY_POINTS["title"];
        if ($series->cover == "/missing_cover.png") $pp += Report::PRIORITY_POINTS["cover"];
        if ($series->summary == null) $pp += Report::PRIORITY_POINTS["synopsis"];
        if ($series->authors == null) $pp += Report::PRIORITY_POINTS["authors"];
        return $pp;
    }
}
