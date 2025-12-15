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
    Schema::create('games', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->decimal('price', 8, 2);
        $table->string('img');
        $table->string('banner')->nullable();
        $table->string('trailer')->nullable();
        $table->json('screenshots')->nullable();
        $table->string('tag')->nullable();
        $table->date('release_date')->nullable();
        $table->string('min_os')->nullable();
        $table->string('min_cpu')->nullable();
        $table->string('min_gpu')->nullable();
        $table->string('min_ram')->nullable();
        $table->string('min_storage')->nullable();
        $table->text('description')->nullable();
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
