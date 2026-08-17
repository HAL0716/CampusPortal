<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Models\CourseOffering as CourseOfferingModel;

final class EloquentCourseOfferingRepository implements CourseOfferingRepository
{
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
