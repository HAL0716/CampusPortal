<?php

namespace Database\Seeders;

use App\Enums\Grade;
use App\Models\Enrollment;
use App\Models\FinalGrade;
use Illuminate\Database\Seeder;

class FinalGradeSeeder extends Seeder
{
    public function run(): void
    {
        Enrollment::all()
            ->each(function ($enrollment) {
                FinalGrade::firstOrCreate(
                    [
                        'enrollment_id' => $enrollment->id,
                    ],
                    [
                        'grade' => fake()->randomElement(Grade::cases()),
                    ]
                );
            });
    }
}
