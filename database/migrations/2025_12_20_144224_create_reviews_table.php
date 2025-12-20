<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Хэрэв reviews хүснэгт байхгүй бол шинээр үүсгэнэ
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Хэрэглэгчийн ID (Хэн бичсэн бэ?)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Тоглоомын ID (Ямар тоглоом бэ?)
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            
            // Үнэлгээ (1-5 од) - nullable болгосон тул од дарахгүй зөвхөн сэтгэгдэл бичиж болно
            $table->integer('rating')->nullable();
            
            // Сэтгэгдэл
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};