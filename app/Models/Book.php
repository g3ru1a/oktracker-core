<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['isbn_10', 'isbn_13', 'title', 'publish_date', 'cover_url',
        'pages', 'series_id', 'volume_number', 'oneshot', 'format', 'synopsis', 'language', 'authors',
        'publisher', 'clean_title', 'book_type_id'];

    public function series(){
        return $this->belongsTo(Series::class);
    }

    public function reports()
    {
        return $this->morphOne(Report::class, 'item');
    }

    public function type(){
        return $this->hasOne(BookType::class);
    }
}
