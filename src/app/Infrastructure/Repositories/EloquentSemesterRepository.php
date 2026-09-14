<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contexts\Semester\Duplicate\SemesterDuplicateDetector;
use App\Application\Contexts\Semester\Duplicate\SemesterDuplicateTarget;
use App\Domain\Semester\Entities\Semester;
use App\Domain\Semester\Exceptions\SemesterAlreadyExistsException;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Semester\ValueObjects\AcademicYear;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Models\Semester as SemesterModel;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Database\QueryException;

final class EloquentSemesterRepository implements SemesterRepository
{
    public function __construct(
        private readonly SemesterDuplicateDetector $duplicateDetector
    ) {}

    public function save(Semester $semester): Semester
    {
        $model = new SemesterModel;

        if ($semester->id() !== null) {
            $model = SemesterModel::find($semester->requireId()->value());

            if ($model === null) {
                throw new SemesterNotFoundException;
            }
        }

        $model->academic_year = $semester->academicYear()->value();
        $model->term = $semester->term();
        $model->start_date = $semester->startDate()->format('Y-m-d');
        $model->end_date = $semester->endDate()->format('Y-m-d');

        try {
            $model->save();
        } catch (QueryException $e) {
            if ($this->duplicateDetector->isDuplicate($e, SemesterDuplicateTarget::ACADEMIC_YEAR_AND_TERM)) {
                throw new SemesterAlreadyExistsException;
            }

            throw $e;
        }

        return $this->toEntity($model);
    }

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

    public function getLatest(): Semester
    {
        $model = SemesterModel::query()
            ->orderByDesc('end_date')
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
