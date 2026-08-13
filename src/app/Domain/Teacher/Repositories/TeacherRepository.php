<?php

namespace App\Domain\Teacher\Repositories;

use App\Domain\Teacher\Entities\Teacher;
use App\Domain\User\ValueObjects\UserId;

interface TeacherRepository
{
    public function findByUserId(UserId $userId): ?Teacher;
}
