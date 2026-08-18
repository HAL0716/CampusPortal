<?php

namespace App\Application\Services\Authorization;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;

interface CourseOfferingAuthorizationService
{
    public function canManage(UserId $userId, CourseOfferingId $courseOfferingId): bool;
}
