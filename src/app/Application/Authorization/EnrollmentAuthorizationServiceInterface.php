<?php

namespace App\Application\Authorization;

use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\User\ValueObjects\UserId;

interface EnrollmentAuthorizationServiceInterface
{
    public function canManage(UserId $userId, EnrollmentId $enrollmentId): bool;
}
