<?php

namespace Tests\Unit\Application\CourseOffering\Management;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\CourseOffering\Management\CourseOfferingDTO;
use App\Application\CourseOffering\Management\ListCourseOfferingsQuery;
use App\Application\CourseOffering\Management\ListCourseOfferingsUseCase;
use App\Application\CourseOffering\Management\StudentDTO;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Teacher\Exceptions\TeacherNotFoundException;
use App\Domain\Teacher\TeacherRepositoryInterface;
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

    private SemesterRepositoryInterface&MockInterface $semesters;

    private TeacherRepositoryInterface&MockInterface $teachers;

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
