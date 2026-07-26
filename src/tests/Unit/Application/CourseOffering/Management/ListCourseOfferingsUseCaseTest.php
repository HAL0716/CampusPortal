<?php

namespace Tests\Unit\Application\CourseOffering\Management;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\CourseOffering\Management\CourseOfferingDTO;
use App\Application\CourseOffering\Management\ListCourseOfferingsQuery;
use App\Application\CourseOffering\Management\ListCourseOfferingsUseCase;
use App\Application\CourseOffering\Management\StudentDTO;
use App\Domain\Academic\Term;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterId;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Teacher\Exceptions\TeacherNotFoundException;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepositoryInterface;
use App\Domain\User\UserId;
use Carbon\CarbonImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\TestCase;

class ListCourseOfferingsUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const USER_ID = 100;

    private const TEACHER_ID = 20;

    private const SEMESTER_ID = 1;

    private const DATE = '2025-04-01';

    private TeacherRepositoryInterface&MockInterface $teachers;

    private SemesterRepositoryInterface&MockInterface $semesters;

    private CourseOfferingQueryServiceInterface&MockInterface $queryService;

    private ListCourseOfferingsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semesters = Mockery::mock(SemesterRepositoryInterface::class);
        $this->teachers = Mockery::mock(TeacherRepositoryInterface::class);
        $this->queryService = Mockery::mock(CourseOfferingQueryServiceInterface::class);

        $this->useCase = new ListCourseOfferingsUseCase(
            $this->semesters,
            $this->teachers,
            $this->queryService,
        );
    }

    public function test_returns_course_offerings_when_teacher_and_semester_exist(): void
    {
        $offerings = [
            new CourseOfferingDTO(
                id: 1,
                name: 'Webプログラミング',
                students: [
                    new StudentDTO(
                        id: 10,
                        studentNumber: 'S001',
                        status: EnrollmentStatus::ENROLLED,
                    ),
                ],
            ),
        ];

        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs(
                fn (CarbonImmutable $date) => $date->equalTo($this->date())
            )
            ->andReturn($this->semester());

        $this->teachers
            ->shouldReceive('findByUserId')
            ->once()
            ->withArgs(
                fn (UserId $id) => $id->value() === self::USER_ID
            )
            ->andReturn($this->teacher());

        $this->queryService
            ->shouldReceive('findForManagement')
            ->once()
            ->withArgs(
                fn (SemesterId $semesterId, TeacherId $teacherId) => $semesterId->value() === self::SEMESTER_ID
                    && $teacherId->value() === self::TEACHER_ID
            )
            ->andReturn($offerings);

        self::assertSame(
            $offerings,
            $this->useCase->execute($this->query()),
        );
    }

    public function test_throws_exception_when_teacher_does_not_exist(): void
    {
        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs(
                fn (CarbonImmutable $date) => $date->equalTo($this->date())
            )
            ->andReturn($this->semester());

        $this->teachers
            ->shouldReceive('findByUserId')
            ->once()
            ->withArgs(
                fn (UserId $id) => $id->value() === self::USER_ID
            )
            ->andReturnNull();

        $this->queryService
            ->shouldNotReceive('findForManagement');

        $this->expectException(TeacherNotFoundException::class);

        $this->useCase->execute($this->query());
    }

    public function test_throws_exception_when_semester_does_not_exist(): void
    {
        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs(
                fn (CarbonImmutable $date) => $date->equalTo($this->date())
            )
            ->andReturnNull();

        $this->teachers
            ->shouldNotReceive('findByUserId');

        $this->queryService
            ->shouldNotReceive('findForManagement');

        $this->expectException(SemesterNotFoundException::class);

        $this->useCase->execute($this->query());
    }

    private function query(): ListCourseOfferingsQuery
    {
        return new ListCourseOfferingsQuery(
            date: $this->date(),
            userId: new UserId(self::USER_ID),
        );
    }

    private function semester(): Semester
    {
        return Semester::reconstruct(
            id: new SemesterId(self::SEMESTER_ID),
            academicYear: '2025',
            term: Term::FIRST,
        );
    }

    private function teacher(): Teacher
    {
        return Teacher::reconstruct(
            id: new TeacherId(self::TEACHER_ID),
            userId: new UserId(self::USER_ID),
        );
    }

    private function date(): CarbonImmutable
    {
        return CarbonImmutable::parse(self::DATE);
    }
}
