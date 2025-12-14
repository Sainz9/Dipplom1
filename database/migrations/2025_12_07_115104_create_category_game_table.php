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
  Schema::create('category_game', function (Blueprint $table) {
        $table->id();
        // Тоглоомын ID
        $table->foreignId('game_id')->constrained()->onDelete('cascade');
        // Категорийн ID
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        
        $table->timestamps();
    });
    Schema::table('games', function (Blueprint $table) {
        $table->date('release_date')->nullable(); // Огноо багана нэмж байна
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_game');
    }
};
