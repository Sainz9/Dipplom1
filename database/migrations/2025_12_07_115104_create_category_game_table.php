<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Анхаар: Хүснэгтийн нэр 'category_game' байх ёстой (цагаан толгойн дарааллаар)
        Schema::create('category_game', function (Blueprint $table) {
            $table->id();

            // Game ID (games хүснэгттэй холбоно)
            $table->foreignId('game_id')->constrained()->onDelete('cascade');

            // Category ID (categories хүснэгттэй холбоно)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_game');
    }
};