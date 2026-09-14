<?php

namespace App\Domain\Student\Policies;

use App\Domain\Student\Exceptions\InsufficientCredits;

final readonly class GraduationPolicy
{
    public function __construct(
        private int $requiredCredits,
    ) {}

    public function assertEligible(int $credits): void
    {
        if ($credits < $this->requiredCredits) {
            throw new InsufficientCredits(
                required: $this->requiredCredits,
                actual: $credits,
            );
        }
    }
}
