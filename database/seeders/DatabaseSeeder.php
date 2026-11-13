<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $adminRole = Role::where('name', 'Administrator')->first();

        User::firstOrCreate(
            ['email' => 'admin@biblioteca.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role_id'  => $adminRole->id,
            ]
        );
    }
}