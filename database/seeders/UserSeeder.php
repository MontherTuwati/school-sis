<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $todayDate = Carbon::now();

        // Create a sample super admin user
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'username' => "sadmin1",
            'email' => 'superadmin@example.com',
            'role' => 'Super Admin',
            'password' => Hash::make('sadmin001'),
            'join_date' => $todayDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a sample admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => "admin1",
            'email' => 'admin@example.com',
            'role' => 'Admin',
            'password' => Hash::make('admin001'),
            'join_date' => $todayDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
