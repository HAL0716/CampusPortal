<?php

namespace App\Infrastructure\Repositories;

use App\Application\Enrollment\EnrollmentDuplicateDetectorInterface;
use App\Application\Enrollment\EnrollmentDuplicateTarget;
use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exceptions\EnrollmentAlreadyExistsException;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Student\StudentId;
use App\Models\Enrollment as EnrollmentModel;
use Illuminate\Database\QueryException;

final class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function __construct(
        private readonly EnrollmentDuplicateDetectorInterface $duplicateDetector
    ) {}

    public function save(Enrollment $enrollment): Enrollment
    {
        $model = new EnrollmentModel;

        if ($enrollment->id() !== null) {
            $model = EnrollmentModel::find($enrollment->requireId()->value());

            if ($model === null) {
                throw new EnrollmentNotFoundException;
            }
        }

        $model->student_id = $enrollment->studentId()->value();
        $model->course_offering_id = $enrollment->courseOfferingId()->value();
        $model->status = $enrollment->status()->value;

        try {
            $model->save();
        } catch (QueryException $e) {
            if ($this->duplicateDetector->isDuplicate($e, EnrollmentDuplicateTarget::STUDENT_COURSE_OFFERING)) {
                throw new EnrollmentAlreadyExistsException;
            }

            throw $e;
        }

        return $this->toEntity($model);
    }

    public function find(
        StudentId $studentId,
        CourseOfferingId $courseOfferingId,
    ): ?Enrollment {
        $model = EnrollmentModel::query()
            ->where('student_id', $studentId->value())
            ->where('course_offering_id', $courseOfferingId->value())
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(EnrollmentModel $model): Enrollment
    {
        return Enrollment::reconstruct(
            new EnrollmentId((int) $model->id),
            new StudentId((int) $model->student_id),
            new CourseOfferingId((int) $model->course_offering_id),
            $model->status
        );
    }
}
