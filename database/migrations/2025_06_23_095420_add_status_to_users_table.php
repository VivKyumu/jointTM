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
        Schema::table('users', function (Blueprint $table) {
            // Check if 'status' column doesn't already exist before adding it
            if (!Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop column if it exists when rolling back
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

};

