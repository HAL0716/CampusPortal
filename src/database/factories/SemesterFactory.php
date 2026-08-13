<?php

namespace Database\Factories;

use App\Domain\Academic\Term;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year' => '2026',
            'term' => Term::FIRST,
            'start_date' => '2026-04-01',
            'end_date' => '2026-07-31',
        ];
    }

    public function second(): static
    {
        return $this->state([
            'academic_year' => '2026',
            'term' => Term::SECOND,
            'start_date' => '2026-08-01',
            'end_date' => '2026-12-31',
        ]);
    }

    public function third(): static
    {
        return $this->state([
            'academic_year' => '2026',
            'term' => Term::THIRD,
            'start_date' => '2027-01-01',
            'end_date' => '2027-03-31',
        ]);
    }
}
