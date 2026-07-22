<?php

namespace Database\Seeders\Data;

use App\Domain\Academic\Term;

final class SemesterData
{
    public static function all(): array
    {
        return [
            [
                'academic_year' => '2025',
                'term' => Term::FIRST->value,
                'start_date' => '2025-04-01',
                'end_date' => '2025-08-31',
            ],
            [
                'academic_year' => '2025',
                'term' => Term::SECOND->value,
                'start_date' => '2025-09-01',
                'end_date' => '2025-12-31',
            ],
            [
                'academic_year' => '2025',
                'term' => Term::THIRD->value,
                'start_date' => '2026-01-01',
                'end_date' => '2026-03-31',
            ],
            [
                'academic_year' => '2026',
                'term' => Term::FIRST->value,
                'start_date' => '2026-04-01',
                'end_date' => '2026-08-31',
            ],
            [
                'academic_year' => '2026',
                'term' => Term::SECOND->value,
                'start_date' => '2026-09-01',
                'end_date' => '2026-12-31',
            ],
            [
                'academic_year' => '2026',
                'term' => Term::THIRD->value,
                'start_date' => '2027-01-01',
                'end_date' => '2027-03-31',
            ],
        ];
    }
}
