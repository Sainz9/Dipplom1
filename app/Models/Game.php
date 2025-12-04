<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

  protected $fillable = [
    'title', 'price', 'sale_price', 'category_id', 
    'img', 'banner', 
    'description', 'tag', 'trailer', 
    'min_os', 'min_cpu', 'min_gpu', 'min_ram', 'min_storage'
];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}