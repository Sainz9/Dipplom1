<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
    {
       Schema::table('reviews', function (Blueprint $table) {
        $table->integer('rating')->nullable()->change();
    
            $table->id();
            // Хэрэглэгчийн ID (Хэн бичсэн бэ?)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Тоглоомын ID (Ямар тоглоом бэ?)
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            
            // Үнэлгээ (1-5 од)
            $table->integer('rating');
            
            // Сэтгэгдэл (Заавал биш)
            $table->text('comment')->nullable();
            
            $table->timestamps(); // Created_at, Updated_at
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
