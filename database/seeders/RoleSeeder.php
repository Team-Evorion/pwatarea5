<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Librarian', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Reader', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}