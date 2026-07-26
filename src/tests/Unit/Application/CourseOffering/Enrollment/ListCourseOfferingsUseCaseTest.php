<?php

namespace Tests\Unit\Application\CourseOffering\Enrollment;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\CourseOffering\Enrollment\CourseOfferingDTO;
use App\Application\CourseOffering\Enrollment\ListCourseOfferingsQuery;
use App\Application\CourseOffering\Enrollment\ListCourseOfferingsUseCase;
use App\Domain\Academic\Term;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterId;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
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

    private const STUDENT_ID = 10;

    private const SEMESTER_ID = 1;

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
        $offerings = [
            new CourseOfferingDTO(
                id: 1,
                name: 'Webプログラミング',
                status: null,
            ),
        ];

        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs(
                fn (CarbonImmutable $date) => $date->equalTo($this->date())
            )
            ->andReturn($this->semester());

        $this->students
            ->shouldReceive('findByUserId')
            ->once()
            ->withArgs(
                fn (UserId $id) => $id->value() === self::USER_ID
            )
            ->andReturn($this->student());

        $this->queryService
            ->shouldReceive('findForEnrollment')
            ->once()
            ->withArgs(
                fn (SemesterId $semesterId, StudentId $studentId) => $semesterId->value() === self::SEMESTER_ID
                    && $studentId->value() === self::STUDENT_ID
            )
            ->andReturn($offerings);

        self::assertSame(
            $offerings,
            $this->useCase->execute($this->query()),
        );
    }

    public function test_throws_exception_when_student_does_not_exist(): void
    {
        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs(
                fn (CarbonImmutable $date) => $date->equalTo($this->date())
            )
            ->andReturn($this->semester());

        $this->students
            ->shouldReceive('findByUserId')
            ->once()
            ->withArgs(
                fn (UserId $id) => $id->value() === self::USER_ID
            )
            ->andReturnNull();

        $this->queryService
            ->shouldNotReceive('findForEnrollment');

        $this->expectException(StudentNotFoundException::class);

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

    private function student(): Student
    {
        return Student::reconstruct(
            id: new StudentId(self::STUDENT_ID),
            userId: new UserId(self::USER_ID),
        );
    }

    private function date(): CarbonImmutable
    {
        return CarbonImmutable::parse('2025-04-01');
    }
}
