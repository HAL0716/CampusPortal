<?php

namespace Tests\Unit\Application\CourseOffering\Enrollment;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\CourseOffering\Enrollment\CourseOfferingDTO;
use App\Application\CourseOffering\Enrollment\ListCourseOfferingsQuery;
use App\Application\CourseOffering\Enrollment\ListCourseOfferingsUseCase;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\StudentRepositoryInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Semester\SemesterTestHelper;
use Tests\Support\Student\StudentTestHelper;
use Tests\TestCase;

class ListCourseOfferingsUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use SemesterTestHelper;
    use StudentTestHelper;

    private SemesterRepositoryInterface&MockInterface $semesters;

    private StudentRepositoryInterface&MockInterface $students;

    private CourseOfferingQueryServiceInterface&MockInterface $queryService;

    private ListCourseOfferingsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semesters = Mockery::mock(SemesterRepositoryInterface::class);
        $this->students = Mockery::mock(StudentRepositoryInterface::class);
        $this->queryService = Mockery::mock(CourseOfferingQueryServiceInterface::class);

        $this->useCase = new ListCourseOfferingsUseCase(
            $this->semesters,
            $this->students,
            $this->queryService,
        );
    }

    public function test_returns_course_offerings_when_student_and_semester_exist(): void
    {
        $semester = $this->semester();
        $student = $this->student();

        $offerings = [
            new CourseOfferingDTO(
                id: 1,
                name: 'Webプログラミング',
                status: null,
            ),
        ];

        $this->expectSemester($this->semesters, $semester);

        $this->expectStudent($this->students, $student);

        $this->queryService
            ->shouldReceive('findForEnrollment')
            ->once()
            ->withArgs(
                fn ($semesterId, $studentId) => $semesterId->value() === $semester->requireId()->value()
                    && $studentId->value() === $student->requireId()->value()
            )
            ->andReturn($offerings);

        self::assertSame(
            $offerings,
            $this->useCase->execute($this->query()),
        );
    }

    public function test_throws_exception_when_student_does_not_exist(): void
    {
        $this->expectSemester($this->semesters, $this->semester());

        $this->expectStudent($this->students, null);

        $this->queryService
            ->shouldNotReceive('findForEnrollment');

        $this->expectException(StudentNotFoundException::class);

        $this->useCase->execute($this->query());
    }

    public function test_throws_exception_when_semester_does_not_exist(): void
    {
        $this->expectSemester($this->semesters, null);

        $this->students
            ->shouldNotReceive('findByUserId');

        $this->queryService
            ->shouldNotReceive('findForEnrollment');

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
