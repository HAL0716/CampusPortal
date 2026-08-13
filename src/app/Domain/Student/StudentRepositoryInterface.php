<?php

namespace App\Domain\Student;

use App\Domain\Student\Entities\Student;
use App\Domain\User\ValueObjects\UserId;

interface StudentRepositoryInterface
{
    public function findByUserId(UserId $userId): ?Student;
}
