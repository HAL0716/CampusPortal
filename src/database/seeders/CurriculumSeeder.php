<?php

namespace Database\Seeders;

use App\Enums\Degree;
use App\Models\Curriculum;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    private const CURRICULUMS = [
        ['degree' => Degree::BACHELOR, 'year' => '1'],
        ['degree' => Degree::BACHELOR, 'year' => '2'],
        ['degree' => Degree::BACHELOR, 'year' => '3'],
        ['degree' => Degree::BACHELOR, 'year' => '4'],
        ['degree' => Degree::MASTER, 'year' => '1'],
        ['degree' => Degree::MASTER, 'year' => '2'],
        ['degree' => Degree::DOCTOR, 'year' => '1'],
        ['degree' => Degree::DOCTOR, 'year' => '2'],
        ['degree' => Degree::DOCTOR, 'year' => '3'],
    ];

    public function run(): void
    {
        foreach (self::CURRICULUMS as $curriculum) {
            Curriculum::firstOrCreate($curriculum);
        }
    }
}
