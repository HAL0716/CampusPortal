<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Teacher\Entities\Teacher;
use App\Domain\Teacher\Repositories\TeacherRepository;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Domain\User\ValueObjects\UserId;
use App\Models\Teacher as TeacherModel;

final class EloquentTeacherRepository implements TeacherRepository
{
    public function findByUserId(UserId $userId): ?Teacher
    {
        $model = TeacherModel::where('user_id', $userId->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(TeacherModel $model): Teacher
    {
        return Teacher::reconstruct(
            new TeacherId((int) $model->id),
            new UserId((int) $model->user_id)
        );
    }
}
