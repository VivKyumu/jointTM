<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            DB::table('users')->where('is_admin', true)->update(['role' => 'admin']);
            DB::table('users')->where(function ($query) {
                $query->whereNull('role')->orWhere('role', 'user');
            })->where('is_admin', false)->update(['role' => 'staff']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            DB::table('users')->where('role', 'staff')->update(['role' => 'user']);
        }
    }
};
