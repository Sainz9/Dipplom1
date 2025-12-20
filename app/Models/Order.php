<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // ЭНЭ ХЭСГИЙГ НЭМЭЭРЭЙ:
    protected $fillable = [
        'user_id',
        'game_id',
        'amount',
        'payment_method',
        'status',
    ];

    // Холбоосууд (Relationships) - Админ хэсэгт хэрэг болно
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}