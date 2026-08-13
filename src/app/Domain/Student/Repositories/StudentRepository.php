<?php

namespace App\Domain\Student\Repositories;

use App\Domain\Student\Entities\Student;
use App\Domain\User\ValueObjects\UserId;

interface StudentRepository
{
    public function findByUserId(UserId $userId): ?Student;
}
