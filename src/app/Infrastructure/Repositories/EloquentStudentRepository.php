<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contexts\Student\Duplicate\StudentDuplicateDetector;
use App\Application\Contexts\Student\Duplicate\StudentDuplicateTarget;
use App\Domain\Department\ValueObjects\DepartmentId;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Exceptions\StudentAlreadyExistsException;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Student\ValueObjects\StudentNumber;
use App\Domain\User\ValueObjects\UserId;
use App\Models\Student as StudentModel;
use Illuminate\Database\QueryException;

final class EloquentStudentRepository implements StudentRepository
{
    public function __construct(
        private readonly StudentDuplicateDetector $duplicateDetector
    ) {}

    public function save(Student $student): Student
    {
        $model = new StudentModel;

        if ($student->id() !== null) {
            $model = StudentModel::find($student->requireId()->value());

            if ($model === null) {
                throw new StudentNotFoundException;
            }
        }

        $model->user_id = $student->userId()->value();
        $model->department_id = $student->departmentId()->value();
        $model->student_number = $student->studentNumber()->value();
        $model->status = $student->status();

        try {
            $model->save();
        } catch (QueryException $e) {
            if ($this->duplicateDetector->isDuplicate($e, StudentDuplicateTarget::STUDENT_NUMBER)) {
                throw new StudentAlreadyExistsException;
            }

            throw $e;
        }

        return $this->toEntity($model);
    }

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
            new UserId((int) $model->user_id),
            new DepartmentId((int) $model->department_id),
            new StudentNumber($model->student_number),
            $model->status,
        );
    }
}
