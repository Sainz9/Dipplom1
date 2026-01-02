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
        'description', 
        'img', 
        'banner', 
        'trailer', 
        'screenshots', 
        'tag', 
        'release_date',
        'download_link',
        'rating',
        
        // Minimum Specs (Эдгээрийг шалга)
        'min_os', 
        'min_cpu', 
        'min_gpu', 
        'min_ram', 
        'min_storage',

        // Recommended Specs (ЭДГЭЭР ДУТУУ БАЙГАА ТУЛ НЭМЭЭРЭЙ)
        'rec_os', 
        'rec_cpu', 
        'rec_gpu', 
        'rec_ram', 
        'rec_storage',



        'developer', 
        'publisher',
        'platform'
    ];

    protected $casts = [
        'screenshots' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_game');
    }
 public function reviews()
{
    
    return $this->hasMany(Review::class)->latest(); 
}
}