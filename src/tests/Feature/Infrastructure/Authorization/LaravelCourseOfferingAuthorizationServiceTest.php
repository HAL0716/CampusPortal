<?php

namespace Tests\Feature\Infrastructure\Authorization;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;
use App\Infrastructure\Authorization\LaravelCourseOfferingAuthorizationService;
use App\Models\CourseOffering;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LaravelCourseOfferingAuthorizationServiceTest extends TestCase
{
    use RefreshDatabase;

    private LaravelCourseOfferingAuthorizationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(LaravelCourseOfferingAuthorizationService::class);
    }

    public function test_can_manage_when_teacher_owns_course_offering(): void
    {
        $user = User::factory()->create();
        $teacher = Teacher::factory()->for($user)->create();

        $courseOffering = CourseOffering::factory()->create();
        $courseOffering->course->teachers()->attach($teacher->id);

        self::assertTrue(
            $this->service->canManage(
                new UserId($user->id),
                new CourseOfferingId($courseOffering->id),
            )
        );
    }

    public function test_cannot_manage_when_user_is_not_teacher(): void
    {
        $user = User::factory()->create();
        $courseOffering = CourseOffering::factory()->create();

        self::assertFalse(
            $this->service->canManage(
                new UserId($user->id),
                new CourseOfferingId($courseOffering->id),
            )
        );
    }

    public function test_cannot_manage_when_teacher_is_not_assigned(): void
    {
        $user = User::factory()->create();
        Teacher::factory()->for($user)->create();

        $courseOffering = CourseOffering::factory()->create();

        self::assertFalse(
            $this->service->canManage(
                new UserId($user->id),
                new CourseOfferingId($courseOffering->id),
            )
        );
    }

    public function test_cannot_manage_when_course_offering_not_found(): void
    {
        $user = User::factory()->create();
        Teacher::factory()->for($user)->create();

        self::assertFalse(
            $this->service->canManage(
                new UserId($user->id),
                new CourseOfferingId(99999),
            )
        );
    }
}
