<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    // Mass Assignment хийхэд эдгээр талбарууд хэрэгтэй
    protected $guarded = []; 

    // Category-тай холбох функц
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}