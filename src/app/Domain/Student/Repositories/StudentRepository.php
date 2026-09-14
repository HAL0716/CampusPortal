<?php

namespace App\Domain\Student\Repositories;

use App\Application\Services\Database\RowLockMode;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\User\ValueObjects\UserId;

interface StudentRepository
{
    public function save(Student $student): Student;

    public function find(StudentId $id): ?Student;

    public function get(StudentId $id, RowLockMode $lockMode = RowLockMode::NONE): Student;

    public function findByUserId(UserId $userId): ?Student;

    public function getByUserId(UserId $userId): Student;
}
