<?php

namespace App\Domain\Teacher;

use App\Domain\Teacher\Entities\Teacher;
use App\Domain\User\ValueObjects\UserId;

interface TeacherRepositoryInterface
{
    public function findByUserId(UserId $userId): ?Teacher;
}
