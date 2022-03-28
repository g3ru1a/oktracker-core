<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'total_books', 'total_cost', 'currency'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($collection) { // before delete() method call this
            $collection->items->each->delete();
            return true;
        });
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items(){
        return $this->hasMany(Item::class);
    }
}
