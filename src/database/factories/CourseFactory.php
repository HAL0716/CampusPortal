<?php

namespace Database\Factories;

use App\Enums\DayOfWeek;
use App\Enums\Period;
use App\Enums\Term;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => '科目',
            'description' => fake()->sentence(),
            'credits' => fake()->numberBetween(1, 20),
            'term' => fake()->randomElement(Term::cases()),
            'default_day_of_week' => fake()->randomElement(DayOfWeek::cases()),
            'default_period' => fake()->randomElement(Period::cases()),
            'default_teacher_id' => null,
        ];
    }
}
