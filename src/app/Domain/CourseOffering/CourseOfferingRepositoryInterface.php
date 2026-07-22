<?php

namespace App\Domain\CourseOffering;

use App\Domain\Student\StudentId;

interface CourseOfferingRepositoryInterface
{
    public function findAll(): array;

    public function findAllForStudent(StudentId $studentId): array;
}
