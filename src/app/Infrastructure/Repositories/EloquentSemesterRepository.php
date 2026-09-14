<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Semester\Entities\Semester;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Semester\ValueObjects\AcademicYear;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Models\Semester as SemesterModel;
use Carbon\CarbonImmutable;
use DateTimeImmutable;

final class EloquentSemesterRepository implements SemesterRepository
{
    public function getByDate(CarbonImmutable $date): Semester
    {
        $model = SemesterModel::query()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if ($model === null) {
            throw new SemesterNotFoundException;
        }

        return $this->toEntity($model);
    }

    private function toEntity(SemesterModel $semester): Semester
    {
        return Semester::reconstruct(
            id: new SemesterId($semester->id),
            academicYear: new AcademicYear($semester->academic_year),
            term: $semester->term,
            startDate: new DateTimeImmutable($semester->start_date->format('Y-m-d')),
            endDate: new DateTimeImmutable($semester->end_date->format('Y-m-d')),
        );
    }
}
