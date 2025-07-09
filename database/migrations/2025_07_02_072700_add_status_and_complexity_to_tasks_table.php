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
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('status_id')->nullable()->constrained('statuses');
            $table->foreignId('complexity_id')->nullable()->constrained('task_complexities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['complexity_id']);
            $table->dropColumn(['status_id', 'complexity_id']);
        });
    }
};
