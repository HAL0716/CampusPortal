<?php

namespace Tests\Feature\Infrastructure\Authorization;

use App\Domain\Enrollment\EnrollmentId;
use App\Domain\User\UserId;
use App\Infrastructure\Authorization\EnrollmentAuthorizationService;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EnrollmentAuthorizationServiceTest extends TestCase
{
    use RefreshDatabase;

    private EnrollmentAuthorizationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(EnrollmentAuthorizationService::class);
    }

    public function test_can_manage_when_teacher_owns_course_offering(): void
    {
        $user = User::factory()->create();
        $teacher = Teacher::factory()->for($user)->create();

        $courseOffering = CourseOffering::factory()->create();
        $courseOffering->course->teachers()->attach($teacher->id);

        $enrollment = Enrollment::factory()->for($courseOffering)->create();

        self::assertTrue(
            $this->service->canManage(
                new UserId($user->id),
                new EnrollmentId($enrollment->id),
            )
        );
    }

    public function test_cannot_manage_when_user_is_not_teacher(): void
    {
        $user = User::factory()->create();

        $enrollment = Enrollment::factory()->create();

        self::assertFalse(
            $this->service->canManage(
                new UserId($user->id),
                new EnrollmentId($enrollment->id),
            )
        );
    }

    public function test_cannot_manage_when_teacher_is_not_assigned(): void
    {
        $user = User::factory()->create();
        Teacher::factory()->for($user)->create();

        $courseOffering = CourseOffering::factory()->create();

        $enrollment = Enrollment::factory()->for($courseOffering)->create();

        self::assertFalse(
            $this->service->canManage(
                new UserId($user->id),
                new EnrollmentId($enrollment->id),
            )
        );
    }

    public function test_cannot_manage_when_enrollment_not_found(): void
    {
        $user = User::factory()->create();
        Teacher::factory()->for($user)->create();

        self::assertFalse(
            $this->service->canManage(
                new UserId($user->id),
                new EnrollmentId(99999),
            )
        );
    }
}
