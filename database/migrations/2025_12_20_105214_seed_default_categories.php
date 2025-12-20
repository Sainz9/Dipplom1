<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Энийг заавал нэмээрэй!

return new class extends Migration
{
    public function up()
    {
        // Оруулах категориудын жагсаалт
        $categories = [
            ['name' => 'Action', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Adventure', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'RPG (Role-Playing)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Strategy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Simulation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Racing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Horror', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'FPS (Shooter)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fighting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Puzzle', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Multiplayer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Open World', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Survival', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Бааз руу оруулах
        DB::table('categories')->insert($categories);
    }

    public function down()
    {
        // Хэрэв rollback хийвэл эдгээрийг устгана (Сонголттой)
        // DB::table('categories')->truncate();
    }
};