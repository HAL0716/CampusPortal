<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contexts\CourseOffering\Duplicate\CourseOfferingDuplicateDetector;
use App\Application\Contexts\CourseOffering\Duplicate\CourseOfferingDuplicateTarget;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\Exceptions\CourseOfferingAlreadyExistsException;
use App\Domain\CourseOffering\Exceptions\CourseOfferingNotFoundException;
use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Models\CourseOffering as CourseOfferingModel;
use Illuminate\Database\QueryException;

final class EloquentCourseOfferingRepository implements CourseOfferingRepository
{
    public function __construct(
        private readonly CourseOfferingDuplicateDetector $duplicateDetector
    ) {}

    public function save(CourseOffering $courseOffering): CourseOffering
    {
        $model = new CourseOfferingModel;

        if ($courseOffering->id() !== null) {
            $model = CourseOfferingModel::find($courseOffering->requireId()->value());

            if ($model === null) {
                throw new CourseOfferingNotFoundException;
            }
        }

        $model->course_id = $courseOffering->courseId()->value();
        $model->semester_id = $courseOffering->semesterId()->value();

        try {
            $model->save();
        } catch (QueryException $e) {
            if ($this->duplicateDetector->isDuplicate($e, CourseOfferingDuplicateTarget::COURSE_SEMESTER)) {
                throw new CourseOfferingAlreadyExistsException;
            }

            throw $e;
        }

        return $this->toEntity($model);
    }

    public function findById(CourseOfferingId $id): ?CourseOffering
    {
        $model = CourseOfferingModel::with('course.teachers')
            ->find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(
        CourseOfferingModel $model
    ): CourseOffering {
        return CourseOffering::reconstruct(
            new CourseOfferingId($model->id),
            new SemesterId($model->semester_id),
            new CourseId($model->course_id),
            $model->course->teachers
                ->map(fn ($teacher) => new TeacherId($teacher->id))
                ->all(),
        );
    }
}
