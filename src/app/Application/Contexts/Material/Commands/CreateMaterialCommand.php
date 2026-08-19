<?php

namespace App\Application\Contexts\Material\Commands;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;

final readonly class CreateMaterialCommand
{
    public function __construct(
        public CourseOfferingId $courseOfferingId,
        public UserId $userId,
    ) {}
}
