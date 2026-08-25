<?php

namespace Tests\Unit\Domain\Enrollment;

use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Enrollment\Exceptions\EnrollmentIdNotAssignedException;
use App\Domain\Enrollment\Exceptions\InvalidEnrollmentStatusException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\EnrollmentTestHelper;

final class EnrollmentTest extends TestCase
{
    use EnrollmentTestHelper;

    public function test_create_returns_enrolled_enrollment_without_id(): void
    {
        $enrollment = $this->createEnrollment();

        $this->assertNull($enrollment->id());
        $this->assertSame($this->studentId()->value(), $enrollment->studentId()->value());
        $this->assertSame($this->courseOfferingId()->value(), $enrollment->courseOfferingId()->value());
        $this->assertSame(EnrollmentStatus::ENROLLED, $enrollment->status());
    }

    public function test_reconstruct_restores_enrollment_with_id_and_status(): void
    {
        $enrollment = $this->reconstructEnrollment(status: EnrollmentStatus::DROPPED);

        $this->assertSame($this->enrollmentId()->value(), $enrollment->id()->value());
        $this->assertSame($this->studentId()->value(), $enrollment->studentId()->value());
        $this->assertSame($this->courseOfferingId()->value(), $enrollment->courseOfferingId()->value());
        $this->assertSame(EnrollmentStatus::DROPPED, $enrollment->status());
    }

    public function test_require_id_returns_assigned_id(): void
    {
        $enrollment = $this->reconstructEnrollment();

        $this->assertSame($this->enrollmentId()->value(), $enrollment->requireId()->value());
    }

    public function test_require_id_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(EnrollmentIdNotAssignedException::class);

        $this->createEnrollment()->requireId();
    }

    public function test_enroll_returns_same_instance_when_already_enrolled(): void
    {
        $enrollment = $this->createEnrollment();

        $this->assertSame($enrollment, $enrollment->enroll());
    }

    public function test_enroll_changes_dropped_status_to_enrolled(): void
    {
        $enrollment = $this->reconstructEnrollment(status: EnrollmentStatus::DROPPED);

        $enrolled = $enrollment->enroll();

        $this->assertNotSame($enrollment, $enrolled);
        $this->assertSame(EnrollmentStatus::ENROLLED, $enrolled->status());
    }

    public function test_enroll_throws_exception_for_completed_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::COMPLETED)->enroll();
    }

    public function test_enroll_throws_exception_for_failed_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::FAILED)->enroll();
    }

    public function test_drop_changes_enrolled_status_to_dropped(): void
    {
        $enrollment = $this->createEnrollment();

        $dropped = $enrollment->drop();

        $this->assertNotSame($enrollment, $dropped);
        $this->assertSame(EnrollmentStatus::DROPPED, $dropped->status());
    }

    public function test_drop_returns_same_instance_when_already_dropped(): void
    {
        $enrollment = $this->reconstructEnrollment(status: EnrollmentStatus::DROPPED);

        $this->assertSame($enrollment, $enrollment->drop());
    }

    public function test_drop_throws_exception_for_completed_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::COMPLETED)->drop();
    }

    public function test_drop_throws_exception_for_failed_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::FAILED)->drop();
    }

    public function test_complete_changes_enrolled_status_to_completed(): void
    {
        $enrollment = $this->createEnrollment();

        $completed = $enrollment->complete();

        $this->assertNotSame($enrollment, $completed);
        $this->assertSame(EnrollmentStatus::COMPLETED, $completed->status());
    }

    public function test_complete_returns_same_instance_when_already_completed(): void
    {
        $enrollment = $this->reconstructEnrollment(status: EnrollmentStatus::COMPLETED);

        $this->assertSame($enrollment, $enrollment->complete());
    }

    public function test_complete_throws_exception_for_dropped_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::DROPPED)->complete();
    }

    public function test_complete_throws_exception_for_failed_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::FAILED)->complete();
    }

    public function test_fail_changes_enrolled_status_to_failed(): void
    {
        $enrollment = $this->createEnrollment();

        $failed = $enrollment->fail();

        $this->assertNotSame($enrollment, $failed);
        $this->assertSame(EnrollmentStatus::FAILED, $failed->status());
    }

    public function test_fail_returns_same_instance_when_already_failed(): void
    {
        $enrollment = $this->reconstructEnrollment(status: EnrollmentStatus::FAILED);

        $this->assertSame($enrollment, $enrollment->fail());
    }

    public function test_fail_throws_exception_for_dropped_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::DROPPED)->fail();
    }

    public function test_fail_throws_exception_for_completed_status(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->reconstructEnrollment(status: EnrollmentStatus::COMPLETED)->fail();
    }
}
