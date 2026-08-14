<?php

namespace Database\Factories;

use App\Domain\Academic\Enums\Term;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(2),
            'description' => fake()->optional()->sentence(),
            'term' => fake()->randomElement(Term::cases()),
        ];
    }
}
