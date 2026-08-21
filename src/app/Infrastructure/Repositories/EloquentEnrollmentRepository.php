<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contexts\Enrollment\Duplicate\EnrollmentDuplicateDetector;
use App\Application\Contexts\Enrollment\Duplicate\EnrollmentDuplicateTarget;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Exceptions\EnrollmentAlreadyExistsException;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Models\Enrollment as EnrollmentModel;
use Illuminate\Database\QueryException;

final class EloquentEnrollmentRepository implements EnrollmentRepository
{
    public function __construct(
        private readonly EnrollmentDuplicateDetector $duplicateDetector
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

    public function findById(EnrollmentId $id): ?Enrollment
    {
        $model = EnrollmentModel::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function getById(EnrollmentId $id): Enrollment
    {
        $enrollment = $this->findById($id);

        if ($enrollment === null) {
            throw new EnrollmentNotFoundException;
        }

        return $enrollment;
    }

    public function findByStudentAndCourseOffering(StudentId $studentId, CourseOfferingId $courseOfferingId): ?Enrollment
    {
        $model = EnrollmentModel::query()
            ->where('student_id', $studentId->value())
            ->where('course_offering_id', $courseOfferingId->value())
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function getByStudentAndCourseOffering(StudentId $studentId, CourseOfferingId $courseOfferingId): Enrollment
    {
        $enrollment = $this->findByStudentAndCourseOffering($studentId, $courseOfferingId);

        if ($enrollment === null) {
            throw new EnrollmentNotFoundException;
        }

        return $enrollment;
    }

    private function toEntity(EnrollmentModel $model): Enrollment
    {
        return Enrollment::reconstruct(
            new EnrollmentId((int) $model->id),
            new StudentId((int) $model->student_id),
            new CourseOfferingId((int) $model->course_offering_id),
            $model->status,
        );
    }
}
