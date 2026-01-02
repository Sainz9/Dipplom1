<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    // Mass Assignment - Энд бичсэн багануудад өгөгдөл хадгалахыг зөвшөөрнө
    protected $fillable = [
        // 1. Үндсэн мэдээлэл
        'title', 
        'price', 
        'sale_price', 
        'description', 
        'tag',            // Жишээ нь: New, Trending, FreeGame
        'release_date',
        'developer', 
        'publisher',
        'platform',       // PC, Mac, Linux гэх мэт

        // 2. Медиа файлууд
        'img',            // Cover Image (Босоо)
        'banner',         // Banner Image (Хэвтээ)
        'trailer',        // Video Link / File
        'screenshots',    // Олон зураг (Array)
        'download_link',  // Тоглоомын файл эсвэл линк

        // 3. Үнэлгээ
        'rating',

        // 4. Minimum Specs (Хамгийн бага үзүүлэлт)
        'min_os', 
        'min_cpu', 
        'min_gpu', 
        'min_ram', 
        'min_storage',

        // 5. Recommended Specs (Зөвлөмжит үзүүлэлт - БҮРЭН НЭМЭГДСЭН)
        'rec_os', 
        'rec_cpu', 
        'rec_gpu', 
        'rec_ram', 
        'rec_storage',
    ];

    // Өгөгдлийн төрлийг хувиргах
    protected $casts = [
        'screenshots' => 'array', // JSON-ийг Array болгож авна (Зураг харуулахад чухал!)
        'release_date' => 'date', // Carbon date объект болгоно (форматлахад амар)
    ];

    /**
     * Relationship: Тоглоом нь олон категорид хамаарна
     */
    public function categories()
    {
        // category_game нь pivot table-ийн нэр
        return $this->belongsToMany(Category::class, 'category_game');
    }

    /**
     * Relationship: Тоглоом нь олон сэтгэгдэлтэй байна
     */
    public function reviews()
    {
        // Хамгийн сүүлд бичигдсэн сэтгэгдэл эхэндээ гарна
        return $this->hasMany(Review::class)->latest(); 
    }
}