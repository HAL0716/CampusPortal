<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contexts\FinalGrade\Duplicate\FinalGradeDuplicateDetector;
use App\Application\Contexts\FinalGrade\Duplicate\FinalGradeDuplicateTarget;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\FinalGrade\Entities\FinalGrade;
use App\Domain\FinalGrade\Exceptions\FinalGradeAlreadyExistsException;
use App\Domain\FinalGrade\Exceptions\FinalGradeNotFoundException;
use App\Domain\FinalGrade\Repositories\FinalGradeRepository;
use App\Domain\FinalGrade\ValueObjects\FinalGradeId;
use App\Models\FinalGrade as FinalGradeModel;
use Illuminate\Database\QueryException;

final class EloquentFinalGradeRepository implements FinalGradeRepository
{
    public function __construct(
        private readonly FinalGradeDuplicateDetector $duplicateDetector
    ) {}

    public function save(FinalGrade $finalGrade): FinalGrade
    {
        $model = new FinalGradeModel;

        if ($finalGrade->id() !== null) {
            $model = FinalGradeModel::find($finalGrade->requireId()->value());

            if ($model === null) {
                throw new FinalGradeNotFoundException;
            }
        }

        $model->enrollment_id = $finalGrade->enrollmentId()->value();
        $model->grade = $finalGrade->grade();

        try {
            $model->save();
        } catch (QueryException $e) {
            if ($this->duplicateDetector->isDuplicate($e, FinalGradeDuplicateTarget::ENROLLMENT_ID)) {
                throw new FinalGradeAlreadyExistsException;
            }

            throw $e;
        }

        return $this->toEntity($model);
    }

    private function toEntity(FinalGradeModel $model): FinalGrade
    {
        return FinalGrade::reconstruct(
            id: new FinalGradeId($model->id),
            enrollmentId: new EnrollmentId($model->enrollment_id),
            grade: $model->grade,
        );
    }
}
