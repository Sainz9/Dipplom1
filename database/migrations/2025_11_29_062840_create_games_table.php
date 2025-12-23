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
        // Хэрэв games хүснэгт байвал устгаад шинээр үүсгэх (Цэвэрхэн эхлэхэд тустай)
        Schema::dropIfExists('games');

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            
            // Category-той холбох
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade'); 
            
            $table->string('title');
            
            // Үнэ (String - учир нь "Тун удахгүй" гэх мэт текст орж магадгүй)
            $table->string('price'); 
            $table->decimal('sale_price', 10, 0)->nullable();
            
            $table->text('img');
            $table->string('banner')->nullable();
            $table->string('trailer')->nullable();
            $table->string('trailer')->nullable();
            $table->json('screenshots')->nullable();
            
            // Recommended Requirements (Зөвлөмжит)
            $table->string('rec_os')->nullable();
            $table->string('rec_cpu')->nullable();
            $table->string('rec_gpu')->nullable();
            $table->string('rec_ram')->nullable();
            $table->string('rec_storage')->nullable();

            // Minimum Requirements (Хамгийн бага)
            $table->string('min_os')->nullable();
            $table->string('min_cpu')->nullable();
            $table->string('min_gpu')->nullable();
            $table->string('min_ram')->nullable();
            $table->string('min_storage')->nullable();

            $table->text('description')->nullable();
            $table->string('tag')->nullable();
            $table->decimal('rating', 2, 1)->nullable(); 
            $table->date('release_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};