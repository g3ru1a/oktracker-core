<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $fillable = 
        ['title', 'language', 'summary', 'publisher', 'authors', 'contributions', 'new'];
}
