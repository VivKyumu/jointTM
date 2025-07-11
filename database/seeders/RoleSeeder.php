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
{User::firstOrCreate(
    ['email' => 'admin@example.com'], // unique field
    [
        'name' => 'Admin User',
        'password' => bcrypt('password'), // or use Hash::make()
        'role' => 'admin',
    ]
);

    

    // Normal user
    User::create([
        'name' => 'Normal User',
        'email' => 'user@example.com',
        'password' => Hash::make('password'),
        'role' => 'user',
    ]);
}

}
