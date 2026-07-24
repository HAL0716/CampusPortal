<?php

namespace Tests\Unit\Application\CourseOffering;

use App\Application\CourseOffering\CourseOfferingListDTO;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\CourseOffering\ListCourseOfferingsQuery;
use App\Application\CourseOffering\ListCourseOfferingsUseCase;
use App\Domain\Academic\Term;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterId;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\User\UserId;
use Carbon\CarbonImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Matcher\Closure;
use Mockery\MockInterface;
use Tests\TestCase;

class ListCourseOfferingsUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const USER_ID = 100;

    private const STUDENT_ID = 10;

    private const SEMESTER_ID = 1;

    private const DATE = '2025-04-01';

    private StudentRepositoryInterface&MockInterface $students;

    private SemesterRepositoryInterface&MockInterface $semesters;

    private CourseOfferingQueryServiceInterface&MockInterface $queryService;

    private ListCourseOfferingsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->students = Mockery::mock(StudentRepositoryInterface::class);
        $this->semesters = Mockery::mock(SemesterRepositoryInterface::class);
        $this->queryService = Mockery::mock(CourseOfferingQueryServiceInterface::class);

        $this->useCase = new ListCourseOfferingsUseCase(
            $this->students,
            $this->semesters,
            $this->queryService,
        );
    }

    public function test_can_get_course_offerings_for_student(): void
    {
        $date = $this->date();

        $semester = Semester::reconstruct(
            id: new SemesterId(self::SEMESTER_ID),
            academicYear: '2025',
            term: Term::FIRST,
        );

        $student = Student::reconstruct(
            id: new StudentId(self::STUDENT_ID),
            userId: new UserId(self::USER_ID),
        );

        $offerings = [
            new CourseOfferingListDTO(
                id: 1,
                name: 'Webプログラミング',
                status: null,
            ),
        ];

        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->with($date)
            ->andReturn($semester);

        $this->students
            ->shouldReceive('findByUserId')
            ->once()
            ->with($this->userId(self::USER_ID))
            ->andReturn($student);

        $this->queryService
            ->shouldReceive('findBySemesterForStudent')
            ->once()
            ->with(
                $this->semesterId(self::SEMESTER_ID),
                $this->studentId(self::STUDENT_ID),
            )
            ->andReturn($offerings);

        $this->assertSame(
            $offerings,
            $this->useCase->execute($this->query($date)),
        );
    }

    public function test_can_get_course_offerings_when_user_is_not_student(): void
    {
        $date = $this->date();

        $semester = Semester::reconstruct(
            id: new SemesterId(self::SEMESTER_ID),
            academicYear: '2025',
            term: Term::FIRST,
        );

        $offerings = [
            new CourseOfferingListDTO(
                id: 1,
                name: 'Webプログラミング',
                status: null,
            ),
        ];

        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->with($date)
            ->andReturn($semester);

        $this->students
            ->shouldReceive('findByUserId')
            ->once()
            ->with($this->userId(self::USER_ID))
            ->andReturn(null);

        $this->queryService
            ->shouldReceive('findBySemester')
            ->once()
            ->with($this->semesterId(self::SEMESTER_ID))
            ->andReturn($offerings);

        $this->assertSame(
            $offerings,
            $this->useCase->execute($this->query($date)),
        );
    }

    public function test_can_not_get_course_offerings_when_semester_does_not_exist(): void
    {
        $date = $this->date();

        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->with($date)
            ->andReturn(null);

        $this->students
            ->shouldNotReceive('findByUserId');

        $this->queryService
            ->shouldNotReceive('findBySemester');

        $this->queryService
            ->shouldNotReceive('findBySemesterForStudent');

        $this->expectException(SemesterNotFoundException::class);

        $this->useCase->execute($this->query($date));
    }

    private function query(CarbonImmutable $date): ListCourseOfferingsQuery
    {
        return new ListCourseOfferingsQuery(
            date: $date,
            userId: new UserId(self::USER_ID),
        );
    }

    private function date(): CarbonImmutable
    {
        return CarbonImmutable::parse(self::DATE);
    }

    private function userId(int $value): Closure
    {
        return Mockery::on(fn (UserId $id) => $id->value() === $value);
    }

    private function studentId(int $value): Closure
    {
        return Mockery::on(fn (StudentId $id) => $id->value() === $value);
    }

    private function semesterId(int $value): Closure
    {
        return Mockery::on(fn (SemesterId $id) => $id->value() === $value);
    }
}
