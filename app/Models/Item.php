<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['book_id', 'collection_id', 'vendor_id', 'price', 'bought_on', 'arrived'];

    public function book(){
        return $this->belongsTo(Book::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function vendor()
    {
        return $this->belongsTo(BookVendor::class, 'vendor_id', 'id');
    }
}
