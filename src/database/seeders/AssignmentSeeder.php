<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\CourseOffering;
use App\Support\SystemClock;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $assignmentsPerOffering = 1;
        $now = SystemClock::now();

        CourseOffering::currentTerm()
            ->each(function (CourseOffering $offering) use ($assignmentsPerOffering, $now) {
                for ($i = 1; $i <= $assignmentsPerOffering; $i++) {
                    $publishedAt = $now->copy()->subDays(fake()->numberBetween(1, 7));

                    Assignment::firstOrCreate(
                        [
                            'course_offering_id' => $offering->id,
                            'title' => "課題 {$i}",
                        ],
                        [
                            'description' => "課題 {$i} の説明",
                            'file_path' => "assignments/{$offering->id}/assignment_{$i}.pdf",
                            'published_at' => $publishedAt,
                            'due_date' => $publishedAt->copy()->addDays(fake()->numberBetween(1, 7)),
                        ]
                    );
                }
            });
    }
}
