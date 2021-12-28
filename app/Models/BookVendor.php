<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'public'];

    public function reports()
    {
        return $this->morphOne(Report::class, 'item');
    }
}
