<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class CourseOfferingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coursesByTerm = Course::all()->groupBy(fn ($course) => $course->term->value);

        foreach (Semester::all() as $semester) {
            foreach ($coursesByTerm->get($semester->term->value, collect()) as $course) {
                CourseOffering::firstOrCreate([
                    'course_id' => $course->id,
                    'semester_id' => $semester->id,
                ]);
            }
        }
    }
}
