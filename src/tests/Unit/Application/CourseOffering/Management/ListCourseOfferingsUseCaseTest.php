<?php

namespace Tests\Unit\Application\Contexts\CourseOffering\Management;

use App\Application\Contexts\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Contexts\CourseOffering\Management\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Management\EnrollmentDTO;
use App\Application\Contexts\CourseOffering\Management\ListCourseOfferingsQuery;
use App\Application\Contexts\CourseOffering\Management\ListCourseOfferingsUseCase;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Teacher\Exceptions\TeacherNotFoundException;
use App\Domain\Teacher\Repositories\TeacherRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Semester\SemesterTestHelper;
use Tests\Support\Teacher\TeacherTestHelper;
use Tests\TestCase;

class ListCourseOfferingsUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use SemesterTestHelper;
    use TeacherTestHelper;

    private SemesterRepository&MockInterface $semesters;

    private TeacherRepository&MockInterface $teachers;

    private CourseOfferingQueryServiceInterface&MockInterface $queryService;

    private ListCourseOfferingsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semesters = Mockery::mock(SemesterRepository::class);
        $this->teachers = Mockery::mock(TeacherRepository::class);
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
                enrollments: [
                    new EnrollmentDTO(
                        id: 10,
                        studentNumber: 'S001',
                        status: EnrollmentStatus::ENROLLED,
                    ),
                ],
            ),
        ];

        $teacher = $this->teacher();

        $this->expectSemester($this->semesters, $this->semester());

        $this->expectTeacher($this->teachers, $teacher);

        $this->queryService
            ->shouldReceive('findForManagement')
            ->once()
            ->withArgs(
                fn ($semesterId, $teacherId) => $semesterId->value() === $this->semester()->requireId()->value()
                    && $teacherId->value() === $teacher->requireId()->value()
            )
            ->andReturn($offerings);

        self::assertSame(
            $offerings,
            $this->useCase->execute($this->query()),
        );
    }

    public function test_throws_exception_when_teacher_does_not_exist(): void
    {
        $this->expectSemester($this->semesters, $this->semester());

        $this->expectTeacher($this->teachers, null);

        $this->queryService
            ->shouldNotReceive('findForManagement');

        $this->expectException(TeacherNotFoundException::class);

        $this->useCase->execute($this->query());
    }

    public function test_throws_exception_when_semester_does_not_exist(): void
    {
        $this->expectSemester($this->semesters, null);

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
            userId: $this->userId(),
        );
    }
}
