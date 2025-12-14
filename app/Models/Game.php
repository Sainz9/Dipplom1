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
        'category_id', // Энд нэг удаа байхад хангалттай
            'tag',
            'rating',
        'img',
        'banner',
        'trailer',
        'screenshots',
        'description',
        'min_os',
        'min_cpu',
        'min_gpu',
        'min_ram',
        'min_storage',
        'release_date'
    ];

    protected $casts = [
        'screenshots' => 'array',
        'release_date' => 'date',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}