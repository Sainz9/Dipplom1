<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    // rating баганыг NULL байж болохоор өөрчилж байна
    DB::statement("ALTER TABLE reviews MODIFY rating INT NULL");
}

public function down()
{
    // Буцаах үед (шаардлагатай бол)
    DB::statement("ALTER TABLE reviews MODIFY rating INT NOT NULL");
}
};
