<?php

namespace Tests\Unit\Application\Contexts\Material;

use App\Application\Contexts\Material\DTOs\MaterialDetailDTO;
use App\Application\Contexts\Material\Queries\GetMaterialDetailQuery;
use App\Application\Contexts\Material\Services\MaterialQueryService;
use App\Application\Contexts\Material\UseCases\GetMaterialDetailUseCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\TestCase;

final class GetMaterialDetailUseCaseTest extends TestCase
{
    use IdTestHelper;
    use MockeryPHPUnitIntegration;

    private MaterialQueryService&MockInterface $materialQueryService;

    private GetMaterialDetailUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->materialQueryService = Mockery::mock(MaterialQueryService::class);

        $this->useCase = new GetMaterialDetailUseCase(
            materialQueryService: $this->materialQueryService,
        );
    }

    public function test_returns_material_detail(): void
    {
        $materialId = $this->materialId();

        $material = new MaterialDetailDTO(
            id: $materialId->value(),
            title: 'PHP入門',
            description: 'PHPの基礎資料',
            filePath: 'materials/php-introduction.pdf',
        );

        $this->materialQueryService
            ->shouldReceive('getDetail')
            ->once()
            ->with($materialId)
            ->andReturn($material);

        $result = $this->useCase->execute(
            new GetMaterialDetailQuery(
                materialId: $materialId,
            ),
        );

        self::assertSame($material, $result);
    }
}
