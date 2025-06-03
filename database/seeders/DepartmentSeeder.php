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
            ['name' => 'Department 1'],
            ['name' => 'Department 2'],
            // Add more departments as needed
        ];

        // Insert data into the departments table
        DB::table('departments')->insert($departments);
    }
}
