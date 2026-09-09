<?php

namespace App\Application\Contexts\Student\Commands;

use App\Domain\Department\ValueObjects\DepartmentId;
use App\Domain\Student\ValueObjects\StudentNumber;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserPassword;

final readonly class CreateStudentCommand
{
    public function __construct(
        public UserEmail $email,
        public UserPassword $password,
        public string $name,
        public StudentNumber $studentNumber,
        public DepartmentId $departmentId,
    ) {}
}
