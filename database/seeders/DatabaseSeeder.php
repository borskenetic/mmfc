<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::create([
            'fname' => 'Admin',
            'lname' => 'User',
            'email' => 'admin@mmfc.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'fname' => 'Staff',
            'lname' => 'User',
            'email' => 'staff@mmfc.local',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $this->call(DemoDataSeeder::class);
    }
}
