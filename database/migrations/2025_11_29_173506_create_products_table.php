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
    // Энд 'games' биш 'products' гэж өгсөн байна
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->decimal('price', 8, 2);
        $table->decimal('sale_price', 8, 2)->nullable();
        $table->string('genre');
        $table->text('description');
        $table->text('long_description')->nullable();
        $table->string('img');
        $table->string('banner');
        $table->string('trailer');
        $table->string('discount')->nullable();
        $table->string('tag')->nullable();
        $table->string('developer')->nullable();
        $table->string('publisher')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
