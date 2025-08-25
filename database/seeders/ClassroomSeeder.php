<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = [
            [
                'name' => 'Computer Lab 1',
                'capacity' => 30,
                'building' => 'Science Building',
                'floor' => '1st Floor',
                'room_number' => 'S101',
                'description' => 'Computer laboratory with 30 workstations',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lecture Hall A',
                'capacity' => 100,
                'building' => 'Main Building',
                'floor' => '2nd Floor',
                'room_number' => 'M201',
                'description' => 'Large lecture hall with projector and sound system',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seminar Room 1',
                'capacity' => 25,
                'building' => 'Library Building',
                'floor' => '1st Floor',
                'room_number' => 'L101',
                'description' => 'Small seminar room for group discussions',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Physics Lab',
                'capacity' => 20,
                'building' => 'Science Building',
                'floor' => '2nd Floor',
                'room_number' => 'S202',
                'description' => 'Physics laboratory with experimental equipment',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conference Room',
                'capacity' => 50,
                'building' => 'Administration Building',
                'floor' => '1st Floor',
                'room_number' => 'A101',
                'description' => 'Conference room with presentation facilities',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('classrooms')->insert($classrooms);
    }
}
