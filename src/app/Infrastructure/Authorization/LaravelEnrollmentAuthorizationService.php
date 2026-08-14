<?php

namespace App\Infrastructure\Authorization;

use App\Application\Services\Authorization\EnrollmentAuthorizationService;
use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Teacher\Repositories\TeacherRepository;
use App\Domain\User\ValueObjects\UserId;

final class LaravelEnrollmentAuthorizationService implements EnrollmentAuthorizationService
{
    public function __construct(
        private readonly TeacherRepository $teachers,
        private readonly EnrollmentRepository $enrollments,
        private readonly CourseOfferingRepository $courseOfferings,
    ) {}

    public function canManage(UserId $userId, EnrollmentId $enrollmentId): bool
    {
        $teacher = $this->teachers->findByUserId($userId);
        if ($teacher === null) {
            return false;
        }

        $enrollment = $this->enrollments->findById($enrollmentId);
        if ($enrollment === null) {
            return false;
        }

        $courseOffering = $this->courseOfferings->findById($enrollment->courseOfferingId());
        if ($courseOffering === null) {
            return false;
        }

        return $courseOffering->hasTeacher($teacher->requireId());
    }
}
