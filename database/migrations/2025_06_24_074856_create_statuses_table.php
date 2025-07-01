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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();  // Status name (unique)
            $table->string('color');           // Hex color code
            $table->integer('order');          // Sorting order
            $table->timestamps();              // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
