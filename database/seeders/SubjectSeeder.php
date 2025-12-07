<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get departments
        $departments = DB::table('departments')->get();
        
        if ($departments->isEmpty()) {
            $this->command->info('No departments found. Please run DepartmentSeeder first.');
            return;
        }

        $csDept = $departments->where('department_name', 'Computer Science')->first();
        $mathDept = $departments->where('department_name', 'Mathematics')->first();
        $physicsDept = $departments->where('department_name', 'Physics')->first();
        $chemistryDept = $departments->where('department_name', 'Chemistry')->first();
        $biologyDept = $departments->where('department_name', 'Biology')->first();
        $englishDept = $departments->where('department_name', 'English Literature')->first();
        $firstDept = $departments->first();

        $subjects = [
            [
                'subject_id' => 'CS101',
                'subject_name' => 'Introduction to Computer Science',
                'class' => 'First Year',
                'department_id' => $csDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'MATH101',
                'subject_name' => 'Calculus I',
                'class' => 'First Year',
                'department_id' => $mathDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'MATH102',
                'subject_name' => 'Algebra',
                'class' => 'First Year',
                'department_id' => $mathDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'PHY101',
                'subject_name' => 'General Physics',
                'class' => 'First Year',
                'department_id' => $physicsDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'CHEM101',
                'subject_name' => 'General Chemistry',
                'class' => 'First Year',
                'department_id' => $chemistryDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'BIO101',
                'subject_name' => 'General Biology',
                'class' => 'First Year',
                'department_id' => $biologyDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'ENG101',
                'subject_name' => 'English Literature',
                'class' => 'First Year',
                'department_id' => $englishDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'CS201',
                'subject_name' => 'Data Structures and Algorithms',
                'class' => 'Second Year',
                'department_id' => $csDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'CS301',
                'subject_name' => 'Web Development',
                'class' => 'Third Year',
                'department_id' => $csDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'MATH201',
                'subject_name' => 'Statistics',
                'class' => 'Second Year',
                'department_id' => $mathDept->id ?? $firstDept->id,
            ],
            [
                'subject_id' => 'HIST101',
                'subject_name' => 'World History',
                'class' => 'First Year',
                'department_id' => $firstDept->id,
            ],
            [
                'subject_id' => 'SCI101',
                'subject_name' => 'General Science',
                'class' => 'First Year',
                'department_id' => $firstDept->id,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Subject data seeded successfully!');
    }
}

