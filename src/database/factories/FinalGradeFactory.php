<?php

namespace Database\Factories;

use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Models\Enrollment;
use App\Models\FinalGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinalGrade>
 */
class FinalGradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'grade' => fake()->randomElement(FinalGradeType::cases()),
        ];
    }
}
