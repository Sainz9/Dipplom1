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
        // Хэрэв хүснэгт аль хэдийн байвал алдаа өгөхгүйн тулд эхлээд шалгана
        if (!Schema::hasTable('category_game')) {
            Schema::create('category_game', function (Blueprint $table) {
                $table->id();
                
                // Game ID (Гадаад түлхүүр)
                $table->foreignId('game_id')->constrained()->onDelete('cascade');
                
                // Category ID (Гадаад түлхүүр)
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.we
     */
    public function down(): void
    {
        Schema::dropIfExists('category_game');
    }
};