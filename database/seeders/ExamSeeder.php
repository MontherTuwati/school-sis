<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Subject;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some courses and subjects for reference
        $courses = Course::all();
        $subjects = Subject::all();

        if ($courses->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('No courses or subjects found. Please run CourseSeeder and SubjectSeeder first.');
            return;
        }

        $examData = [
            [
                'title' => 'Midterm Mathematics',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Math%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'midterm',
                'exam_date' => Carbon::now()->addDays(7),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'duration' => 120,
                'total_marks' => 100,
                'passing_marks' => 40,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Bring calculator and writing materials. No electronic devices allowed.',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Final English Literature',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%English%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'final',
                'exam_date' => Carbon::now()->addDays(14),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'duration' => 120,
                'total_marks' => 100,
                'passing_marks' => 50,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Essay writing exam. Bring blue/black pens only.',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Science Quiz',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Science%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'quiz',
                'exam_date' => Carbon::now()->addDays(3),
                'start_time' => '10:00:00',
                'end_time' => '10:30:00',
                'duration' => 30,
                'total_marks' => 20,
                'passing_marks' => 10,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Multiple choice questions. 30 minutes duration.',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'History Assignment',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%History%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'assignment',
                'exam_date' => Carbon::now()->addDays(10),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'duration' => 480,
                'total_marks' => 50,
                'passing_marks' => 25,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Research paper on World War II. Submit online by 5 PM.',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Computer Science Practical',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Computer%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'practical',
                'exam_date' => Carbon::now()->addDays(5),
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'duration' => 120,
                'total_marks' => 100,
                'passing_marks' => 60,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Programming practical in Lab 101. Bring USB drive.',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Physics Midterm',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Physics%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'midterm',
                'exam_date' => Carbon::now()->subDays(5),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'duration' => 120,
                'total_marks' => 100,
                'passing_marks' => 40,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Bring calculator and formula sheet.',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Chemistry Quiz',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Chemistry%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'quiz',
                'exam_date' => Carbon::now()->subDays(2),
                'start_time' => '14:00:00',
                'end_time' => '14:30:00',
                'duration' => 30,
                'total_marks' => 20,
                'passing_marks' => 10,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Short quiz on chemical reactions.',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Biology Final',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Biology%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'final',
                'exam_date' => Carbon::now()->addDays(21),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'duration' => 120,
                'total_marks' => 100,
                'passing_marks' => 50,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Comprehensive final exam covering all topics.',
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($examData as $exam) {
            Exam::create($exam);
        }

        $this->command->info('Exam data seeded successfully!');
    }
}
