<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Classroom;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some courses, subjects, and classrooms for reference
        $courses = Course::all();
        $subjects = Subject::all();
        $classrooms = Classroom::all();

        if ($courses->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('No courses or subjects found. Please run CourseSeeder and SubjectSeeder first.');
            return;
        }

        // Get first user for created_by
        $firstUserId = \App\Models\User::first()?->id ?? 1;

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
                'created_by' => $firstUserId
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
                'created_by' => $firstUserId
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
                'created_by' => $firstUserId
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
                'created_by' => $firstUserId
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
                'created_by' => $firstUserId
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
                'created_by' => $firstUserId
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
                'created_by' => $firstUserId
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
                'created_by' => $firstUserId,
                'classroom_id' => $classrooms->first()?->id ?? null
            ],
            [
                'title' => 'Algebra Quiz - Today',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Math%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'quiz',
                'exam_date' => Carbon::today(),
                'start_time' => '11:00:00',
                'end_time' => '11:30:00',
                'duration' => 30,
                'total_marks' => 25,
                'passing_marks' => 12,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Quick quiz on algebraic equations. No calculator needed.',
                'is_active' => true,
                'created_by' => $firstUserId,
                'classroom_id' => $classrooms->skip(1)->first()?->id ?? null
            ],
            [
                'title' => 'Literature Midterm',
                'course_id' => $courses->skip(1)->first()?->id ?? $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%English%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'midterm',
                'exam_date' => Carbon::now()->addDays(12),
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'duration' => 120,
                'total_marks' => 100,
                'passing_marks' => 45,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Essay-based exam. Bring pens and paper.',
                'is_active' => true,
                'created_by' => $firstUserId,
                'classroom_id' => $classrooms->first()?->id ?? null
            ],
            [
                'title' => 'Programming Assignment',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Computer%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'assignment',
                'exam_date' => Carbon::now()->addDays(8),
                'start_time' => '09:00:00',
                'end_time' => '23:59:59',
                'duration' => 900,
                'total_marks' => 75,
                'passing_marks' => 38,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Complete the web application project. Submit via GitHub by midnight.',
                'is_active' => true,
                'created_by' => $firstUserId,
                'classroom_id' => null
            ],
            [
                'title' => 'Statistics Final Exam',
                'course_id' => $courses->skip(1)->first()?->id ?? $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Math%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'final',
                'exam_date' => Carbon::now()->addDays(30),
                'start_time' => '13:00:00',
                'end_time' => '15:30:00',
                'duration' => 150,
                'total_marks' => 150,
                'passing_marks' => 75,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Comprehensive final exam. Calculator and formula sheet allowed.',
                'is_active' => true,
                'created_by' => $firstUserId,
                'classroom_id' => $classrooms->skip(1)->first()?->id ?? null
            ],
            [
                'title' => 'Organic Chemistry Practical',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Chemistry%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'practical',
                'exam_date' => Carbon::now()->addDays(6),
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'duration' => 180,
                'total_marks' => 100,
                'passing_marks' => 60,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Lab practical exam. Bring lab coat and safety goggles.',
                'is_active' => true,
                'created_by' => $firstUserId,
                'classroom_id' => $classrooms->first()?->id ?? null
            ],
            [
                'title' => 'World History Quiz',
                'course_id' => $courses->skip(1)->first()?->id ?? $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%History%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'quiz',
                'exam_date' => Carbon::now()->subDays(10),
                'start_time' => '10:00:00',
                'end_time' => '10:45:00',
                'duration' => 45,
                'total_marks' => 30,
                'passing_marks' => 15,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Multiple choice and short answer questions.',
                'is_active' => true,
                'created_by' => $firstUserId,
                'classroom_id' => $classrooms->first()?->id ?? null
            ],
            [
                'title' => 'Data Structures Midterm',
                'course_id' => $courses->first()->id,
                'subject_id' => $subjects->where('name', 'like', '%Computer%')->first()?->id ?? $subjects->first()->id,
                'exam_type' => 'midterm',
                'exam_date' => Carbon::now()->addDays(9),
                'start_time' => '09:30:00',
                'end_time' => '11:30:00',
                'duration' => 120,
                'total_marks' => 100,
                'passing_marks' => 50,
                'semester' => 'Fall 2025',
                'academic_year' => '2025-2026',
                'instructions' => 'Theory and problem-solving exam. No electronic devices.',
                'is_active' => true,
                'created_by' => $firstUserId,
                'classroom_id' => $classrooms->skip(1)->first()?->id ?? null
            ]
        ];

        foreach ($examData as $exam) {
            Exam::create($exam);
        }

        $this->command->info('Exam data seeded successfully!');
    }
}
