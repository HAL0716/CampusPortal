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
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Matcher\Closure;
use Mockery\MockInterface;
use Tests\TestCase;

class ListCourseOfferingsUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const SEMESTER_ID = 1;

    private SemesterRepositoryInterface&MockInterface $semesters;

    private CourseOfferingQueryServiceInterface&MockInterface $queryService;

    private ListCourseOfferingsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semesters = Mockery::mock(SemesterRepositoryInterface::class);
        $this->queryService = Mockery::mock(CourseOfferingQueryServiceInterface::class);

        $this->useCase = new ListCourseOfferingsUseCase(
            $this->semesters,
            $this->queryService,
        );
    }

    public function test_can_get_course_offerings(): void
    {
        $semester = Semester::reconstruct(
            id: new SemesterId(self::SEMESTER_ID),
            academicYear: '2025',
            term: Term::FIRST,
        );

        $offerings = [
            new CourseOfferingListDTO(
                id: 1,
                name: 'Webプログラミング',
            ),
        ];

        $this->semesters
            ->shouldReceive('find')
            ->once()
            ->with('2025', Term::FIRST)
            ->andReturn($semester);

        $this->queryService
            ->shouldReceive('findBySemesterId')
            ->once()
            ->with($this->semesterId(self::SEMESTER_ID))
            ->andReturn($offerings);

        $this->assertSame($offerings, $this->useCase->execute($this->query()));
    }

    public function test_can_not_get_course_offerings_when_semester_does_not_exist(): void
    {
        $this->semesters
            ->shouldReceive('find')
            ->once()
            ->with('2025', Term::FIRST)
            ->andReturn(null);

        $this->queryService
            ->shouldNotReceive('findBySemesterId');

        $this->expectException(SemesterNotFoundException::class);

        $this->useCase->execute($this->query());
    }

    private function query(): ListCourseOfferingsQuery
    {
        return new ListCourseOfferingsQuery(
            academicYear: '2025',
            term: Term::FIRST,
        );
    }

    private function semesterId(int $value): Closure
    {
        return Mockery::on(fn (SemesterId $id) => $id->value() === $value);
    }
}
