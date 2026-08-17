<?php

namespace Database\Factories;

use App\Domain\Student\Enums\StudentStatus;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'student_number' => fake()->unique()->numerify('S########'),
            'status' => StudentStatus::ACTIVE,
        ];
    }
}
