<?php

namespace Database\Factories;

use App\Domain\Enrollment\EnrollmentStatus;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_offering_id' => CourseOffering::factory(),
            'status' => EnrollmentStatus::ENROLLED,
        ];
    }
}
