<?php

namespace App\Domain\Teacher;

use App\Domain\User\UserId;

interface TeacherRepositoryInterface
{
    public function findByUserId(UserId $userId): ?Teacher;
}
