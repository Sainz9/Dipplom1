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
            
            // Foreign Key (Category) - change() гэдгийг хассан
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade'); 
            
            $table->string('title');
            
            // Үнэ (String эсвэл Decimal, таны сонголтоор. Би String болголоо учир нь та "Тун удахгүй" гэж бичнэ гэсэн)
            $table->string('price'); 
            $table->decimal('sale_price', 10, 0)->nullable();
            
            $table->text('img');
            $table->string('banner')->nullable();
            $table->string('trailer')->nullable();
            $table->json('screenshots')->nullable();
            
            // Recommended Requirements (Зөвлөмжит)
            $table->string('rec_os')->nullable();
            $table->string('rec_cpu')->nullable();
            $table->string('rec_gpu')->nullable();
            $table->string('rec_ram')->nullable();
            $table->string('rec_storage')->nullable();

            // Minimum Requirements (Хамгийн бага) - ЭДГЭЭРИЙГ НЭМЭХ ХЭРЭГТЭЙ
            $table->string('min_os')->nullable();     // <-- Энэ дутуу байсан
            $table->string('min_cpu')->nullable();    // <-- Энэ дутуу байсан
            $table->string('min_gpu')->nullable();    // <-- Энэ дутуу байсан
            $table->string('min_ram')->nullable();    // <-- Энэ дутуу байсан
            $table->string('min_storage')->nullable();

            $table->text('description')->nullable();
            $table->string('tag')->nullable();
            $table->decimal('rating', 2, 1)->nullable(); 
            $table->date('release_date')->nullable(); // Release date нэмэв

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