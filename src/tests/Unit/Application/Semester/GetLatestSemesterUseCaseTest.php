<?php

namespace Tests\Unit\Application\Semester;

use App\Application\Contexts\Semester\DTOs\SemesterDTO;
use App\Application\Contexts\Semester\Services\SemesterQueryService;
use App\Application\Contexts\Semester\UseCases\GetLatestSemesterUseCase;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class GetLatestSemesterUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SemesterQueryService&MockInterface $queryService;

    private GetLatestSemesterUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryService = Mockery::mock(SemesterQueryService::class);

        $this->useCase = new GetLatestSemesterUseCase(
            $this->queryService,
        );
    }

    public function test_returns_latest_semester(): void
    {
        $expected = new SemesterDTO(
            id: '1',
            academicYear: '2026',
            term: '1',
            startDate: '2026-04-01',
            endDate: '2026-06-30',
        );

        $this->queryService->shouldReceive('getLatest')
            ->once()
            ->andReturn($expected);

        $result = $this->useCase->execute();

        self::assertSame($expected, $result);
    }

    public function test_throws_exception_when_no_semester_found(): void
    {
        $this->queryService->shouldReceive('getLatest')
            ->once()
            ->andThrow(new SemesterNotFoundException);

        $this->expectException(SemesterNotFoundException::class);

        $this->useCase->execute();
    }
}
