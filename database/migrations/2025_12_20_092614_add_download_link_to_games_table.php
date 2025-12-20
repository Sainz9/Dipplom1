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
    Schema::table('games', function (Blueprint $table) {
        $table->string('download_link')->nullable()->after('description'); 
        // Эсвэл text('download_link') байж болно (урт линк бол)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            //
        });
    }
};
