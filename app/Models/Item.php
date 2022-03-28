<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['book_id', 'collection_id', 'vendor_id', 'price', 'bought_on', 'arrived'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item) { // before delete() method call this
            $item->activity()->delete();
            return true;
        });
    }

    public function setBoughtOnAttribute($value)
    {
        $this->attributes['bought_on'] = (new Carbon($value))->format('Y-m-d H:i:s');
    }

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

    public function activity()
    {
        return $this->hasMany(SocialActivity::class);
    }
}
