<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ManagerRoleSeeder extends Seeder
{
    public function run(): void
    {
        User::whereIn('email', [
            'grace.wanjiku@example.com',
            'brian.otieno@example.com',
            'mercy.achieng@example.com',
            'kevin.mwangi@example.com',
            'faith.njeri@example.com',
        ])->update([
            'role' => 'manager',
            'is_admin' => false,
        ]);
    }
}
