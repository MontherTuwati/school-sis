<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Bachelor of Computer Science',
                'description' => 'Comprehensive computer science program covering programming, algorithms, and software engineering.',
            ],
            [
                'title' => 'Bachelor of Mathematics',
                'description' => 'Advanced mathematics program focusing on pure and applied mathematics.',
            ],
            [
                'title' => 'Bachelor of Engineering',
                'description' => 'Engineering program covering various engineering disciplines.',
            ],
            [
                'title' => 'Bachelor of Science',
                'description' => 'General science program with focus on physics, chemistry, and biology.',
            ],
            [
                'title' => 'Bachelor of Arts',
                'description' => 'Liberal arts program covering literature, history, and social sciences.',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        $this->command->info('Course data seeded successfully!');
    }
}

