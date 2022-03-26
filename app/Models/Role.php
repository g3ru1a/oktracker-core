<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public const USER = 1;
    public const ADMIN = 2;
    public const DATA_ANALYST = 3;

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
