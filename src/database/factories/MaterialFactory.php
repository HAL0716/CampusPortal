<?php

namespace Database\Factories;

use App\Models\CourseOffering;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_offering_id' => CourseOffering::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'file_path' => fake()->optional()->filePath(),
            'publish_date' => fake()->optional()->dateTime(),
        ];
    }
}
