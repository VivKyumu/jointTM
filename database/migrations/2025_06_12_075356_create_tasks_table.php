<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FK
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps(); // includes created_at & updated_at
            $table->dateTime('due_at')->nullable(); // Will be set in model/controller
            $table->foreignId('complexity_id')->constrained()->onDelete('set null')->nullable(); // New F
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
