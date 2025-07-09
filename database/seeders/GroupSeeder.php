<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\Group::create(['name' => 'Administrators']);
    \App\Models\Group::create(['name' => 'Users']);
    \App\Models\Group::create(['name' => 'Guests']);
}
}
