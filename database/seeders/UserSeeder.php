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

        // Create a sample department manager user
        DB::table('users')->insert([
            'name' => 'Department Manager',
            'username' => "manager1",
            'email' => 'departmentmanager@example.com',
            'role' => 'Department Manager',
            'password' => Hash::make('manager001'),
            'join_date' => $todayDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a sample teacher user
        DB::table('users')->insert([
            'name' => 'Teacher',
            'username' => "teacher1",
            'email' => 'teacher@example.com',
            'role' => 'Teacher',
            'password' => Hash::make('teacher001'),
            'join_date' => $todayDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a sample teacher user
        DB::table('users')->insert([
            'name' => 'Student',
            'username' => "student1",
            'email' => 'student@example.com',
            'role' => 'Student',
            'password' => Hash::make('student001'),
            'join_date' => $todayDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
