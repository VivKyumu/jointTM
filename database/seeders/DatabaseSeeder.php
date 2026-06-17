<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::whereIn('email', ['test@example.com', 'user@example.com'])
            ->orWhere('email', 'like', '%@example.org')
            ->orWhere('email', 'like', '%@example.net')
            ->delete();

        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'is_admin' => true],
            ['name' => 'Vivian Mbachi', 'email' => 'vivian.mbachi@example.com', 'is_admin' => false],
            ['name' => 'Eric Kyumu', 'email' => 'eric.kyumu@example.com', 'is_admin' => false],
            ['name' => 'Grace Wanjiku', 'email' => 'grace.wanjiku@example.com', 'is_admin' => false],
            ['name' => 'Brian Otieno', 'email' => 'brian.otieno@example.com', 'is_admin' => false],
            ['name' => 'Mercy Achieng', 'email' => 'mercy.achieng@example.com', 'is_admin' => false],
        ];

        $seedEmails = collect($users)->pluck('email')->all();

        User::where(function ($query) {
                $query->where('email', 'like', '%@example.com')
                    ->orWhere('email', 'like', '%@example.org')
                    ->orWhere('email', 'like', '%@example.net');
            })
            ->whereNotIn('email', $seedEmails)
            ->delete();

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password123'),
                    'role' => $user['is_admin'] ? 'admin' : 'staff',
                    'is_admin' => $user['is_admin'],
                    'is_active' => true,
                ]
            );
        }

        // Seed default application data
        $this->call([
            RoleSeeder::class,
            StatusSeeder::class,
            GroupSeeder::class,
            TaskComplexitiesTableSeeder::class,
            TaskSeeder::class,
            DemoDataSeeder::class,
            ManagerRoleSeeder::class,
        ]);
    }
}
