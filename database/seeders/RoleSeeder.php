<?php

namespace Database\Seeders;

 use App\Models\User;
 use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    // Admin user
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);

    // Normal user
    User::create([
        'name' => 'Normal User',
        'email' => 'user@example.com',
        'password' => Hash::make('password'),
        'role' => 'user',
    ]);
}

}
