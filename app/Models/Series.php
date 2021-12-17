<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $fillable = 
        ['title', 'language', 'summary', 'publisher', 'authors', 'contributions', 'new', 'kind'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function reports()
    {
        return $this->morphOne(Report::class, 'item');
    }
}
