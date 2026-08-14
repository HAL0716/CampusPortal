<?php

namespace Tests\Unit\Domain\Enrollment;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Exceptions\EnrollmentIdNotAssignedException;
use App\Domain\Enrollment\Exceptions\InvalidEnrollmentStatusException;
use App\Domain\Student\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class EnrollmentTest extends TestCase
{
    public function test_can_create_enrollment(): void
    {
        $enrollment = Enrollment::create(new StudentId(1), new CourseOfferingId(10));

        self::assertNull($enrollment->id());
        self::assertSame(1, $enrollment->studentId()->value());
        self::assertSame(10, $enrollment->courseOfferingId()->value());
        $this->assertStatus(EnrollmentStatus::ENROLLED, $enrollment);
    }

    public function test_can_reconstruct_enrollment(): void
    {
        $enrollment = $this->enrollment(status: EnrollmentStatus::COMPLETED);

        self::assertSame(1, $enrollment->id()->value());
        $this->assertStatus(EnrollmentStatus::COMPLETED, $enrollment);
    }

    public function test_can_enroll_when_status_is_enrolled(): void
    {
        $this->assertStatus(
            EnrollmentStatus::ENROLLED,
            $this->enrollment()->enroll()
        );
    }

    public function test_can_enroll_when_status_is_dropped(): void
    {
        $this->assertStatus(
            EnrollmentStatus::ENROLLED,
            $this->enrollment(status: EnrollmentStatus::DROPPED)->enroll()
        );
    }

    public function test_can_not_enroll_when_status_is_completed(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->enrollment(status: EnrollmentStatus::COMPLETED)->enroll();
    }

    public function test_can_drop_when_status_is_enrolled(): void
    {
        $this->assertStatus(
            EnrollmentStatus::DROPPED,
            $this->enrollment(status: EnrollmentStatus::ENROLLED)->drop()
        );
    }

    public function test_can_drop_when_status_is_dropped(): void
    {
        $this->assertStatus(
            EnrollmentStatus::DROPPED,
            $this->enrollment(status: EnrollmentStatus::DROPPED)->drop()
        );
    }

    public function test_can_not_drop_when_status_is_completed(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->enrollment(status: EnrollmentStatus::COMPLETED)->drop();
    }

    public function test_can_complete_when_status_is_enrolled(): void
    {
        $this->assertStatus(
            EnrollmentStatus::COMPLETED,
            $this->enrollment(status: EnrollmentStatus::ENROLLED)->complete()
        );
    }

    public function test_can_not_complete_when_status_is_dropped(): void
    {
        $this->expectException(InvalidEnrollmentStatusException::class);

        $this->enrollment(status: EnrollmentStatus::DROPPED)->complete();
    }

    public function test_can_complete_when_status_is_completed(): void
    {
        $this->assertStatus(
            EnrollmentStatus::COMPLETED,
            $this->enrollment(status: EnrollmentStatus::COMPLETED)->complete()
        );
    }

    public function test_require_id_throws_exception_when_id_is_null(): void
    {
        $this->expectException(EnrollmentIdNotAssignedException::class);

        Enrollment::create(new StudentId(1), new CourseOfferingId(10))->requireId();
    }

    public function test_can_get_id(): void
    {
        self::assertSame(1, $this->enrollment()->requireId()->value());
    }

    private function enrollment(
        int $id = 1,
        int $studentId = 1,
        int $courseOfferingId = 10,
        EnrollmentStatus $status = EnrollmentStatus::ENROLLED
    ): Enrollment {
        return Enrollment::reconstruct(
            id: new EnrollmentId($id),
            studentId: new StudentId($studentId),
            courseOfferingId: new CourseOfferingId($courseOfferingId),
            status: $status
        );
    }

    private function assertStatus(EnrollmentStatus $status, Enrollment $enrollment): void
    {
        self::assertSame($status, $enrollment->status());
    }
}
