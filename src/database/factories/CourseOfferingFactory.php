<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseOffering>
 */
class CourseOfferingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'semester_id' => Semester::factory(),
        ];
    }

    /**
     * @param  array<Teacher>  $teachers
     */
    public function forTeachers(array $teachers): static
    {
        return $this->state([
            'course_id' => Course::factory()->forTeachers($teachers),
        ]);
    }
}
