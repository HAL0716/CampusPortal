<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Course\CourseId;
use App\Domain\CourseOffering\CourseOffering;
use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\CourseOffering\CourseOfferingRepositoryInterface;
use App\Domain\Semester\SemesterId;
use App\Domain\Teacher\TeacherId;
use App\Models\CourseOffering as CourseOfferingModel;

final class CourseOfferingRepository implements CourseOfferingRepositoryInterface
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
