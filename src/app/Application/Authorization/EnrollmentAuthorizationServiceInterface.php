<?php

namespace App\Application\Authorization;

use App\Domain\Enrollment\EnrollmentId;
use App\Domain\User\UserId;

interface EnrollmentAuthorizationServiceInterface
{
    public function canManage(UserId $userId, EnrollmentId $enrollmentId): bool;
}
