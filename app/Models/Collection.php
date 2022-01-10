<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'total_books', 'total_cost', 'currency'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
