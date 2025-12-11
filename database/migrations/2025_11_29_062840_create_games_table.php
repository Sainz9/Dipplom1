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
    Schema::create('games', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->decimal('price', 10, 0)->default(0);
        $table->decimal('sale_price', 10, 0)->nullable();
        $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
        
        $table->text('img');
        $table->string('banner')->nullable();
        $table->string('trailer')->nullable();
        $table->string('screenshot1')->nullable();
        $table->string('screenshot2')->nullable();
        $table->string('min_os')->nullable();
        $table->string('min_cpu')->nullable();
        $table->string('min_gpu')->nullable();
        $table->string('min_ram')->nullable();
        $table->string('min_storage')->nullable();
        $table->text('description')->nullable();
        $table->string('tag')->nullable();
        $table->decimal('rating', 2, 1)->nullable(); 
      

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::dropIfExists('games'); // Энийг заавал нэмэх ёстой!
    }
};