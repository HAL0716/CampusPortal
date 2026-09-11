<?php

namespace Tests\Unit\Domain\Student;

use App\Domain\Student\Exceptions\InsufficientCredits;
use App\Domain\Student\Policies\GraduationPolicy;
use PHPUnit\Framework\TestCase;

final class GraduationPolicyTest extends TestCase
{
    private const REQUIRED_CREDITS = 124;

    private GraduationPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new GraduationPolicy(
            requiredCredits: self::REQUIRED_CREDITS,
        );
    }

    public function test_allows_graduation_when_credits_are_sufficient(): void
    {
        $this->policy->assertEligible(self::REQUIRED_CREDITS);

        $this->addToAssertionCount(1);
    }

    public function test_allows_graduation_when_credits_exceed_requirement(): void
    {
        $this->policy->assertEligible(self::REQUIRED_CREDITS + 1);

        $this->addToAssertionCount(1);
    }

    public function test_rejects_graduation_when_credits_are_insufficient(): void
    {
        $this->expectException(InsufficientCredits::class);

        $this->policy->assertEligible(self::REQUIRED_CREDITS - 1);
    }
}
