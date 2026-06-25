<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\CourseOffering;
use Illuminate\Database\Seeder;

class CourseOfferingSeeder extends Seeder
{
    public function run(): void
    {
        $term = AcademicTerm::current();

        if (! $term) {
            $this->command->warn('No active academic term found.');

            return;
        }

        Course::where('term', $term->term)
            ->each(function (Course $course) use ($term) {
                CourseOffering::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'academic_term_id' => $term->id,
                    ],
                    [
                        'teacher_id' => $course->default_teacher_id,
                        'day_of_week' => $course->default_day_of_week,
                        'period' => $course->default_period,
                    ]
                );
            });
    }
}
