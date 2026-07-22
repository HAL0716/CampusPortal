<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Academic\Term;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterId;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Models\Semester as SemesterModel;

final class SemesterRepository implements SemesterRepositoryInterface
{
    public function find(int $academicYear, Term $term): ?Semester
    {
        $model = SemesterModel::query()
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(SemesterModel $semester): Semester
    {
        return Semester::reconstruct(
            id: new SemesterId($semester->id),
            academicYear: $semester->academic_year,
            term: $semester->term,
        );
    }
}
