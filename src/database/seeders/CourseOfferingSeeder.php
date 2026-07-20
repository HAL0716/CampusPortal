<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseOffering;
use Illuminate\Database\Seeder;

class CourseOfferingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::all()->each(function (Course $course) {
            CourseOffering::firstOrCreate([
                'course_id' => $course->id,
            ]);
        });
    }
}
