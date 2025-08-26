<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample data for departments
        $departments = [
            ['department_name' => 'Computer Science'],
            ['department_name' => 'Mathematics'],
            ['department_name' => 'Physics'],
            ['department_name' => 'Chemistry'],
            ['department_name' => 'Biology'],
            ['department_name' => 'Engineering'],
            ['department_name' => 'Business Administration'],
            ['department_name' => 'Economics'],
            ['department_name' => 'Psychology'],
            ['department_name' => 'English Literature'],
        ];

        // Insert data into the departments table
        DB::table('departments')->insert($departments);
    }
}
