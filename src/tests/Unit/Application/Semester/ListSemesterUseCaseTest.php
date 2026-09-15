<?php

namespace Tests\Unit\Application\Semester;

use App\Application\Contexts\Semester\DTOs\SemesterDTO;
use App\Application\Contexts\Semester\Services\SemesterQueryService;
use App\Application\Contexts\Semester\UseCases\ListSemesterUseCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class ListSemesterUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SemesterQueryService&MockInterface $queryService;

    private ListSemesterUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryService = Mockery::mock(SemesterQueryService::class);

        $this->useCase = new ListSemesterUseCase(
            $this->queryService,
        );
    }

    public function test_returns_semester_list(): void
    {
        $expected = [
            new SemesterDTO(
                id: '1',
                academicYear: '2023',
                term: 'FIRST',
                startDate: '2023-01-01',
                endDate: '2023-03-31',
            ),
            new SemesterDTO(
                id: '2',
                academicYear: '2023',
                term: 'SECOND',
                startDate: '2023-04-01',
                endDate: '2023-06-30',
            ),
        ];

        $this->queryService->shouldReceive('findAll')
            ->once()
            ->andReturn($expected);

        $result = $this->useCase->execute();

        self::assertSame($expected, $result);
    }
}
