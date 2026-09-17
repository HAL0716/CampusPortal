<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Academic\Enums\Term;
use App\Domain\Course\Entities\Course;
use App\Domain\Course\Repositories\CourseRepository;
use App\Domain\Course\ValueObjects\CourseId;
use App\Models\Course as CourseModel;

final class EloquentCourseRepository implements CourseRepository
{
    /** @return array<Course> */
    public function getByTerm(Term $term): array
    {
        return CourseModel::query()
            ->where('term', $term)
            ->get()
            ->map(fn (CourseModel $model) => $this->toEntity($model))
            ->all();
    }

    private function toEntity(CourseModel $model): Course
    {
        return Course::reconstruct(
            id: new CourseId($model->id),
        );
    }
}
