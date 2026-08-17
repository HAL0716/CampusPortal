<?php

namespace Database\Factories;

use App\Domain\Teacher\Enums\TeacherStatus;
use App\Models\Position;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
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
            'position_id' => Position::factory(),
            'status' => TeacherStatus::ACTIVE,
        ];
    }
}
