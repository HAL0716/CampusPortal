<?php

namespace Tests\Unit\Application\CourseOffering\Administration;

use App\Application\CourseOffering\Administration\CourseOfferingDTO;
use App\Application\CourseOffering\Administration\ListCourseOfferingsQuery;
use App\Application\CourseOffering\Administration\ListCourseOfferingsUseCase;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Academic\Term;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Semester;
use App\Domain\Semester\SemesterId;
use App\Domain\Semester\SemesterRepositoryInterface;
use Carbon\CarbonImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
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

    public function test_returns_course_offerings_when_semester_exists(): void
    {
        $semester = $this->semester();

        $offerings = [
            new CourseOfferingDTO(
                id: 1,
                name: 'Webプログラミング',
            ),
        ];

        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs(fn (CarbonImmutable $date) => $date->equalTo($this->date()))
            ->andReturn($semester);

        $this->queryService
            ->shouldReceive('findForAdministration')
            ->once()
            ->withArgs(fn (SemesterId $id) => $id->value() === self::SEMESTER_ID)
            ->andReturn($offerings);

        self::assertSame(
            $offerings,
            $this->useCase->execute($this->query()),
        );
    }

    public function test_throws_exception_when_semester_does_not_exist(): void
    {
        $this->semesters
            ->shouldReceive('findByDate')
            ->once()
            ->withArgs(fn (CarbonImmutable $date) => $date->equalTo($this->date()))
            ->andReturnNull();

        $this->queryService
            ->shouldNotReceive('findForAdministration');

        $this->expectException(SemesterNotFoundException::class);

        $this->useCase->execute($this->query());
    }

    private function query(): ListCourseOfferingsQuery
    {
        return new ListCourseOfferingsQuery(
            date: $this->date(),
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

    private function date(): CarbonImmutable
    {
        return CarbonImmutable::parse('2025-04-01');
    }
}
