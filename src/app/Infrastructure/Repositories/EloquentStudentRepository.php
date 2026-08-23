<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Student\Entities\Student;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\User\ValueObjects\UserId;
use App\Models\Student as StudentModel;

final class EloquentStudentRepository implements StudentRepository
{
    public function findByUserId(UserId $userId): ?Student
    {
        $model = StudentModel::where('user_id', $userId->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function getByUserId(UserId $userId): Student
    {
        $student = $this->findByUserId($userId);

        if ($student === null) {
            throw new StudentNotFoundException;
        }

        return $student;
    }

    private function toEntity(StudentModel $model): Student
    {
        return Student::reconstruct(
            new StudentId((int) $model->id),
            new UserId((int) $model->user_id)
        );
    }
}
