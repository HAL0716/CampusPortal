<?php

namespace Database\Seeders;

use App\Models\CourseOffering;
use App\Models\LectureMaterial;
use App\Support\SystemClock;
use Illuminate\Database\Seeder;

class LectureMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materialsPerOffering = 2;
        $now = SystemClock::now();

        CourseOffering::currentTerm()
            ->each(function (CourseOffering $offering) use ($materialsPerOffering, $now) {
                for ($i = 1; $i <= $materialsPerOffering; $i++) {
                    LectureMaterial::firstOrCreate(
                        [
                            'course_offering_id' => $offering->id,
                            'title' => "講義資料 {$i}",
                        ],
                        [
                            'description' => "講義資料 {$i} の説明",
                            'file_path' => "lecture_materials/{$offering->id}/material_{$i}.pdf",
                            'published_at' => $now->copy()->subDays(fake()->numberBetween(0, 7)),
                        ]
                    );
                }
            });
    }
}
