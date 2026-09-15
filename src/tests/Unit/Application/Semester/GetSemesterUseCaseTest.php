<?php

namespace Tests\Unit\Application\Semester;

use App\Application\Contexts\Semester\DTOs\CourseOfferingDTO;
use App\Application\Contexts\Semester\DTOs\SemesterDetailDTO;
use App\Application\Contexts\Semester\Queries\GetSemesterQuery;
use App\Application\Contexts\Semester\Services\SemesterQueryService;
use App\Application\Contexts\Semester\UseCases\GetSemesterUseCase;
use App\Domain\Semester\ValueObjects\SemesterId;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\TestHelpers\IdTestHelper;
use Tests\TestCase;

final class GetSemesterUseCaseTest extends TestCase
{
    use IdTestHelper;
    use MockeryPHPUnitIntegration;

    private SemesterQueryService&MockInterface $semesterQueryService;

    private GetSemesterUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semesterQueryService = Mockery::mock(SemesterQueryService::class);

        $this->useCase = new GetSemesterUseCase(
            $this->semesterQueryService,
        );
    }

    public function test_execute_returns_semester_detail(): void
    {
        $semesterId = $this->semesterId();

        $expected = new SemesterDetailDTO(
            id: $semesterId->value(),
            academicYear: '2026',
            term: '1',
            startDate: '2026-04-01',
            endDate: '2026-08-31',
            courseOfferings: [
                new CourseOfferingDTO(
                    id: 1,
                    name: 'test 1',
                    description: 'test 1 description',
                ),
                new CourseOfferingDTO(
                    id: 2,
                    name: 'test 2',
                    description: 'test 2 description',
                ),
            ],
        );

        $this->semesterQueryService->shouldReceive('getDetail')
            ->once()
            ->with($semesterId)
            ->andReturn($expected);

        $result = $this->useCase->execute(
            $this->query($semesterId),
        );

        $this->assertSame($expected, $result);
    }

    private function query(SemesterId $semesterId): GetSemesterQuery
    {
        return new GetSemesterQuery(
            semesterId: $semesterId,
        );
    }
}
