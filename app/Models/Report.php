<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'details', 'completed', 'item_id', 'item_type'];

    public function item()
    {
        return $this->morphTo();
    }
}
