<?php

namespace Tests\Unit\Infrastructure\Authorization;

use App\Domain\Course\Repositories\CourseTeacherRepository;
use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\Teacher\Repositories\TeacherRepository;
use App\Infrastructure\Authorization\LaravelCourseOfferingAuthorizationService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Matchers\UseMatcher;
use Tests\Support\TestHelpers\CourseOfferingTestHelper;
use Tests\Support\TestHelpers\TeacherTestHelper;
use Tests\TestCase;

final class LaravelCourseOfferingAuthorizationServiceTest extends TestCase
{
    use CourseOfferingTestHelper;
    use MockeryPHPUnitIntegration;
    use TeacherTestHelper;
    use UseMatcher;

    private TeacherRepository&MockInterface $teachers;

    private CourseOfferingRepository&MockInterface $courseOfferings;

    private CourseTeacherRepository&MockInterface $courseTeachers;

    private LaravelCourseOfferingAuthorizationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teachers = Mockery::mock(TeacherRepository::class);
        $this->courseOfferings = Mockery::mock(CourseOfferingRepository::class);
        $this->courseTeachers = Mockery::mock(CourseTeacherRepository::class);

        $this->service = new LaravelCourseOfferingAuthorizationService(
            teachers: $this->teachers,
            courseOfferings: $this->courseOfferings,
            courseTeachers: $this->courseTeachers,
        );
    }

    public function test_can_manage_course_offering_when_teacher_is_assigned(): void
    {
        $teacher = $this->reconstructTeacher();
        $offering = $this->reconstructCourseOffering();

        $this->teachers->shouldReceive('findByUserId')
            ->once()
            ->with(Mockery::on($this->idMatcher($this->userId())))
            ->andReturn($teacher);

        $this->courseOfferings->shouldReceive('findById')
            ->once()
            ->with(Mockery::on($this->idMatcher($offering->id())))
            ->andReturn($offering);

        $this->courseTeachers->shouldReceive('exists')
            ->once()
            ->with(
                Mockery::on($this->idMatcher($offering->courseId())),
                Mockery::on($this->idMatcher($teacher->requireId())),
            )
            ->andReturnTrue();

        self::assertTrue($this->service->canManage($this->userId(), $offering->id()));
    }

    public function test_cannot_manage_course_offering_when_teacher_does_not_exist(): void
    {
        $this->teachers->shouldReceive('findByUserId')
            ->once()
            ->with(Mockery::on($this->idMatcher($this->userId())))
            ->andReturnNull();

        $this->courseOfferings->shouldNotReceive('findById');

        $this->courseTeachers->shouldNotReceive('exists');

        self::assertFalse($this->service->canManage($this->userId(), $this->courseOfferingId()));
    }

    public function test_cannot_manage_course_offering_when_course_offering_does_not_exist(): void
    {
        $teacher = $this->reconstructTeacher();

        $this->teachers->shouldReceive('findByUserId')
            ->once()
            ->with(Mockery::on($this->idMatcher($this->userId())))
            ->andReturn($teacher);

        $this->courseOfferings->shouldReceive('findById')
            ->once()
            ->with(Mockery::on($this->idMatcher($this->courseOfferingId())))
            ->andReturnNull();

        $this->courseTeachers->shouldNotReceive('exists');

        self::assertFalse($this->service->canManage($this->userId(), $this->courseOfferingId()));
    }

    public function test_cannot_manage_course_offering_when_teacher_is_not_assigned(): void
    {
        $teacher = $this->reconstructTeacher();
        $offering = $this->reconstructCourseOffering();

        $this->teachers->shouldReceive('findByUserId')
            ->once()
            ->with(Mockery::on($this->idMatcher($this->userId())))
            ->andReturn($teacher);

        $this->courseOfferings->shouldReceive('findById')
            ->once()
            ->with(Mockery::on($this->idMatcher($offering->id())))
            ->andReturn($offering);

        $this->courseTeachers->shouldReceive('exists')
            ->once()
            ->with(
                Mockery::on($this->idMatcher($offering->courseId())),
                Mockery::on($this->idMatcher($teacher->requireId())),
            )
            ->andReturnFalse();

        self::assertFalse($this->service->canManage($this->userId(), $offering->id()));
    }
}
