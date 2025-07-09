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
        Schema::create('task_complexities', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Name of complexity level (e.g., "Simple", "Complex")
            $table->integer('level')->unique();   // Numeric level (1-5) with unique constraint
            $table->string('color')->nullable();  // Color code for visual representation
            $table->timestamps();                 // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_complexities');
    }
};