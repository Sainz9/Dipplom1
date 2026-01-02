<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // Энэ функц ЗААВАЛ байх ёстой
    public function games()
    {
        return $this->belongsToMany(Game::class, 'category_game');
    }
}