<?php

namespace App\Domain\Student;

use App\Domain\User\UserId;

interface StudentRepositoryInterface
{
    public function findByUserId(UserId $userId): ?Student;
}
