<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\User\UserId;
use App\Models\Student as StudentModel;

final class StudentRepository implements StudentRepositoryInterface
{
    public function findByUserId(UserId $userId): ?Student
    {
        $model = StudentModel::where('user_id', $userId->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(StudentModel $model): Student
    {
        return Student::reconstruct(
            new StudentId((int) $model->id),
            new UserId((int) $model->user_id)
        );
    }
}
