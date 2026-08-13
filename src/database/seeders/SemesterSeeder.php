<?php

namespace Database\Seeders;

use App\Models\Semester;
use Database\Seeders\Data\SemesterData;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SemesterData::all() as $semesterData) {
            Semester::firstOrCreate(
                [
                    'academic_year' => $semesterData['academic_year'],
                    'term' => $semesterData['term'],
                ],
                $semesterData
            );
        }
    }
}
