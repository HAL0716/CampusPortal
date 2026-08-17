<?php

namespace App\Application\Services\Authorization;

use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\User\ValueObjects\UserId;

interface EnrollmentAuthorizationService
{
    public function canManage(UserId $userId, EnrollmentId $enrollmentId): bool;
}
