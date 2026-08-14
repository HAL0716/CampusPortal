<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Semester\Entities\Semester;
use App\Domain\Semester\SemesterId;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Models\Semester as SemesterModel;
use Carbon\CarbonImmutable;

final class SemesterRepository implements SemesterRepositoryInterface
{
    public function findByDate(CarbonImmutable $date): ?Semester
    {
        $model = SemesterModel::query()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
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
