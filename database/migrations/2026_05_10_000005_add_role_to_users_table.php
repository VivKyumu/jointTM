<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('staff')->after('email');
            });
        }

        DB::table('users')->where('is_admin', true)->update(['role' => 'admin']);
        DB::table('users')->where('is_admin', false)->where(function ($query) {
            $query->whereNull('role')->orWhere('role', 'user');
        })->update(['role' => 'staff']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
