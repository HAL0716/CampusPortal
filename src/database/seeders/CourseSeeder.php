<?php

namespace Database\Seeders;

use App\Domain\Course\CourseTerm;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => '数学基礎',
                'description' => '数学の基礎を学習する講義',
                'term' => CourseTerm::FIRST,
            ],
            [
                'name' => '数学応用',
                'description' => '数学の応用を学習する講義',
                'term' => CourseTerm::SECOND,
            ],
            [
                'name' => '英語基礎',
                'description' => '英語の基礎を学習する講義',
                'term' => CourseTerm::SECOND,
            ],
            [
                'name' => '英語応用',
                'description' => '英語の応用を学習する講義',
                'term' => CourseTerm::THIRD,
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                [
                    'name' => $course['name'],
                ],
                [
                    'description' => $course['description'],
                    'term' => $course['term'],
                ]
            );
        }
    }
}
