<?php

namespace App\Infrastructure\Authorization;

use App\Application\Services\Authorization\CourseOfferingAuthorizationService;
use App\Domain\Course\Repositories\CourseTeacherRepository;
use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Teacher\Repositories\TeacherRepository;
use App\Domain\User\ValueObjects\UserId;

final class LaravelCourseOfferingAuthorizationService implements CourseOfferingAuthorizationService
{
    public function __construct(
        private readonly TeacherRepository $teachers,
        private readonly CourseOfferingRepository $courseOfferings,
        private readonly CourseTeacherRepository $courseTeachers,
    ) {}

    public function canManage(UserId $userId, CourseOfferingId $courseOfferingId): bool
    {
        $teacher = $this->teachers->findByUserId($userId);
        if ($teacher === null) {
            return false;
        }

        $courseOffering = $this->courseOfferings->findById($courseOfferingId);
        if ($courseOffering === null) {
            return false;
        }

        return $this->courseTeachers->exists($courseOffering->courseId(), $teacher->requireId());
    }
}
