<?php

namespace Tests\Unit\Application\Student;

use App\Application\Contexts\Student\DTOs\StudentDetailDTO;
use App\Application\Contexts\Student\Queries\GetStudentQuery;
use App\Application\Contexts\Student\Services\StudentQueryService;
use App\Application\Contexts\Student\UseCases\GetStudentUseCase;
use App\Domain\Student\ValueObjects\StudentId;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\TestHelpers\IdTestHelper;
use Tests\TestCase;

final class GetStudentUseCaseTest extends TestCase
{
    use IdTestHelper;
    use MockeryPHPUnitIntegration;

    private StudentQueryService&MockInterface $studentQueryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studentQueryService = Mockery::mock(StudentQueryService::class);
    }

    public function test_returns_student_detail(): void
    {
        $studentId = $this->studentId();

        $expected = new StudentDetailDTO(
            id: $studentId->value(),
            name: 'John Doe',
            studentNumber: 'S1234567',
            department: 'Computer Science',
            credits: 30,
        );

        $this->studentQueryService
            ->shouldReceive('getDetail')
            ->once()
            ->with($studentId)
            ->andReturn($expected);

        $result = $this->useCase()->execute(
            $this->query($studentId),
        );

        self::assertSame($expected, $result);
    }

    private function useCase(): GetStudentUseCase
    {
        return new GetStudentUseCase(
            queryService: $this->studentQueryService,
        );
    }

    private function query(StudentId $studentId): GetStudentQuery
    {
        return new GetStudentQuery(
            studentId: $studentId,
        );
    }
}
