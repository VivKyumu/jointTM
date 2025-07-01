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
    Schema::create('add_role_to_users', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('role_id');

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

        $table->timestamps();
    });
}

};
