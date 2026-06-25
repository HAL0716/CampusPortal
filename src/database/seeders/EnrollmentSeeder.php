<?php

namespace Database\Seeders;

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseOffering::currentTerm()
            ->with('course.courseTargets')
            ->each(function (CourseOffering $offering) {
                $course = $offering->course;

                $curriculumIds =
                    $course->courseTargets
                        ->pluck('curriculum_id');

                StudentProfile::where('department_id', $course->department_id)
                    ->whereIn('curriculum_id', $curriculumIds)
                    ->each(function ($student) use ($offering) {
                        Enrollment::firstOrCreate(
                            [
                                'student_profile_id' => $student->id,
                                'course_offering_id' => $offering->id,
                            ]
                        );
                    });
            });
    }
}
