<?php

namespace Database\Seeders;

use App\Enums\Term;
use App\Models\AcademicTerm;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AcademicTermSeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        foreach (Term::cases() as $term) {
            $dates = match ($term) {
                Term::FIRST => [
                    'registration_start' => Carbon::create($year, 4, 1),
                    'registration_end' => Carbon::create($year, 4, 10),
                    'lecture_start' => Carbon::create($year, 4, 1),
                    'lecture_end' => Carbon::create($year, 8, 31),
                ],
                Term::SECOND => [
                    'registration_start' => Carbon::create($year, 9, 1),
                    'registration_end' => Carbon::create($year, 9, 10),
                    'lecture_start' => Carbon::create($year, 9, 1),
                    'lecture_end' => Carbon::create($year, 12, 31),
                ],
                Term::THIRD => [
                    'registration_start' => Carbon::create($year + 1, 1, 1),
                    'registration_end' => Carbon::create($year + 1, 1, 10),
                    'lecture_start' => Carbon::create($year + 1, 1, 1),
                    'lecture_end' => Carbon::create($year + 1, 3, 31),
                ],
            };

            AcademicTerm::firstOrCreate(
                [
                    'academic_year' => $year,
                    'term' => $term,
                ],
                $dates
            );
        }
    }
}
