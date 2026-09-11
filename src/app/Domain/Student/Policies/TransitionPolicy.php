<?php

namespace App\Domain\Student\Policies;

use App\Domain\Student\Enums\StudentStatus;

final readonly class TransitionPolicy
{
    public function __construct(
        private GraduationPolicy $graduation,
    ) {}

    public function assertAllowed(
        StudentStatus $to,
        int $credits,
    ): void {
        if ($to === StudentStatus::GRADUATED) {
            $this->graduation->assertEligible($credits);
        }
    }
}
