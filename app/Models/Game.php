<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
protected $fillable = [
    'title',
    'price',
    'sale_price',
    'category_id',
    'tag',
    'rating',
    'img',
    'banner',
    'trailer',
    'screenshot1',
    'screenshot2',
    'description',
    'min_os',
    'min_cpu',
    'min_gpu',
    'min_ram',
    'min_storage',
    'release_date'
];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}