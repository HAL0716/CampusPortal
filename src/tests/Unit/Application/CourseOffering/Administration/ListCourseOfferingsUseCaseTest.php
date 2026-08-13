<?php

namespace Tests\Unit\Application\CourseOffering\Administration;

use App\Application\CourseOffering\Administration\CourseOfferingDTO;
use App\Application\CourseOffering\Administration\ListCourseOfferingsQuery;
use App\Application\CourseOffering\Administration\ListCourseOfferingsUseCase;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Matchers\UseMatcher;
use Tests\Support\Semester\SemesterTestHelper;
use Tests\TestCase;

class ListCourseOfferingsUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use SemesterTestHelper;
    use UseMatcher;

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
        $offerings = [
            new CourseOfferingDTO(
                id: 1,
                name: 'Webプログラミング',
            ),
        ];

        $semester = $this->semester();

        $this->expectSemester($this->semesters, $semester);

        $this->queryService
            ->shouldReceive('findForAdministration')
            ->once()
            ->withArgs($this->idMatcher($semester->requireId()))
            ->andReturn($offerings);

        self::assertSame(
            $offerings,
            $this->useCase->execute($this->query()),
        );
    }

    public function test_throws_exception_when_semester_does_not_exist(): void
    {
        $this->expectSemester($this->semesters, null);

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
}
