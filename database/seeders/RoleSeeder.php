<?php

namespace Database\Seeders;

 use App\Models\User;
 use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'is_active' => true,
            ]
        );
    }

}
