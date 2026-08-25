<?php

namespace Tests\Unit\Application\Contexts\Material;

use App\Application\Contexts\Material\Commands\DownloadMaterialCommand;
use App\Application\Contexts\Material\UseCases\DownloadMaterialUseCase;
use App\Application\Services\Clock\Clock;
use App\Application\Services\Storage\DownloadFile;
use App\Application\Services\Storage\FileStorage;
use App\Domain\Material\Entities\Material;
use App\Domain\Material\Repositories\MaterialRepository;
use Carbon\CarbonImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\TestCase;

final class DownloadMaterialUseCaseTest extends TestCase
{
    use IdTestHelper;
    use MockeryPHPUnitIntegration;

    private MaterialRepository&MockInterface $materials;

    private FileStorage&MockInterface $fileStorage;

    private Clock&MockInterface $clock;

    private DownloadMaterialUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->materials = Mockery::mock(MaterialRepository::class);
        $this->fileStorage = Mockery::mock(FileStorage::class);
        $this->clock = Mockery::mock(Clock::class);

        $this->useCase = new DownloadMaterialUseCase(
            materialRepository: $this->materials,
            fileStorage: $this->fileStorage,
            clock: $this->clock,
        );
    }

    public function test_returns_download_file(): void
    {
        $materialId = $this->materialId();
        $filePath = 'materials/php-introduction.pdf';
        $downloadFile = new DownloadFile(
            path: '/storage/app/private/'.$filePath,
            fileName: 'download.pdf',
        );

        $this->materials
            ->shouldReceive('getById')
            ->once()
            ->with($materialId)
            ->andReturn(Material::reconstruct(
                id: $materialId,
                courseOfferingId: $this->courseOfferingId(),
                title: 'PHP入門',
                description: null,
                filePath: $filePath,
                publishDate: null,
            ));

        $this->clock
            ->shouldReceive('now')
            ->once()
            ->andReturn(CarbonImmutable::parse('2026-04-01 00:00:00'));

        $this->fileStorage
            ->shouldReceive('resolveDownload')
            ->once()
            ->with($filePath, Mockery::type('string'))
            ->andReturn($downloadFile);

        $result = $this->useCase->execute(
            new DownloadMaterialCommand(materialId: $materialId),
        );

        self::assertSame($downloadFile, $result);
    }
}
