<?php

namespace Database\Seeders;

use App\Models\Course;
use Database\Seeders\Data\CourseData;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (CourseData::all() as $course) {
            Course::firstOrCreate(
                [
                    'name' => $course['name'],
                ],
                $course
            );
        }
    }
}
