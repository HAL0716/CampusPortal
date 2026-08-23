<?php

namespace Tests\Unit\Application\Material;

use App\Application\Contexts\Material\Commands\StoreMaterialCommand;
use App\Application\Contexts\Material\UseCases\StoreMaterialUseCase;
use App\Application\Exceptions\ForbiddenException;
use App\Application\Services\Authorization\CourseOfferingAuthorizationService;
use App\Application\Services\Storage\FileStorage;
use App\Application\Services\Storage\UploadFile;
use App\Domain\Material\Repositories\MaterialRepository;
use App\Infrastructure\Storage\Exceptions\FileStorageException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\Support\Matchers\UseMatcher;
use Tests\TestCase;

class StoreMaterialUseCaseTest extends TestCase
{
    use IdTestHelper;
    use MockeryPHPUnitIntegration;
    use UseMatcher;

    private MaterialRepository&MockInterface $materials;

    private CourseOfferingAuthorizationService&MockInterface $auth;

    private FileStorage&MockInterface $fileStorage;

    private StoreMaterialUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->materials = Mockery::mock(MaterialRepository::class);
        $this->auth = Mockery::mock(CourseOfferingAuthorizationService::class);
        $this->fileStorage = Mockery::mock(FileStorage::class);

        $this->useCase = new StoreMaterialUseCase(
            $this->materials,
            $this->auth,
            $this->fileStorage,
        );
    }

    public function test_saves_file_and_material_when_authorized(): void
    {
        $command = $this->command();
        $filePath = 'materials/test.pdf';

        $this->auth->shouldReceive('canManage')
            ->once()
            ->withArgs($this->idsMatcher(
                $command->userId,
                $command->courseOfferingId,
            ))
            ->andReturnTrue();

        $this->fileStorage->shouldReceive('store')
            ->once()
            ->with($command->file, 'materials')
            ->andReturn($filePath);

        $this->materials->shouldReceive('save')
            ->once()
            ->andReturnUsing(function ($material) use ($command, $filePath) {
                self::assertSame($command->courseOfferingId, $material->courseOfferingId());
                self::assertSame($command->title, $material->title());
                self::assertSame($command->description, $material->description());
                self::assertSame($filePath, $material->filePath());
                self::assertSame($command->publishDate, $material->publishDate());

                return $material;
            });

        $this->fileStorage->shouldNotReceive('delete');

        $this->useCase->execute($command);
    }

    public function test_throws_exception_when_user_is_not_authorized(): void
    {
        $command = $this->command();

        $this->auth->shouldReceive('canManage')
            ->once()
            ->withArgs($this->idsMatcher(
                $command->userId,
                $command->courseOfferingId,
            ))
            ->andReturnFalse();

        $this->fileStorage->shouldNotReceive('store');
        $this->fileStorage->shouldNotReceive('delete');
        $this->materials->shouldNotReceive('save');

        $this->expectException(ForbiddenException::class);

        $this->useCase->execute($command);
    }

    public function test_deletes_file_when_material_save_fails(): void
    {
        $command = $this->command();
        $filePath = 'materials/test.pdf';
        $exception = new FileStorageException;

        $this->auth->shouldReceive('canManage')
            ->once()
            ->withArgs($this->idsMatcher(
                $command->userId,
                $command->courseOfferingId,
            ))
            ->andReturnTrue();

        $this->fileStorage->shouldReceive('store')
            ->once()
            ->with($command->file, 'materials')
            ->andReturn($filePath);

        $this->materials->shouldReceive('save')
            ->once()
            ->andThrow($exception);

        $this->fileStorage->shouldReceive('delete')
            ->once()
            ->with($filePath);

        $this->expectExceptionObject($exception);

        $this->useCase->execute($command);
    }

    public function test_does_not_delete_file_when_file_storage_fails(): void
    {
        $command = $this->command();
        $exception = new FileStorageException;

        $this->auth->shouldReceive('canManage')
            ->once()
            ->withArgs($this->idsMatcher(
                $command->userId,
                $command->courseOfferingId,
            ))
            ->andReturnTrue();

        $this->fileStorage->shouldReceive('store')
            ->once()
            ->with($command->file, 'materials')
            ->andThrow($exception);

        $this->fileStorage->shouldNotReceive('delete');
        $this->materials->shouldNotReceive('save');

        $this->expectExceptionObject($exception);

        $this->useCase->execute($command);
    }

    private function command(): StoreMaterialCommand
    {
        return new StoreMaterialCommand(
            userId: $this->userId(),
            courseOfferingId: $this->courseOfferingId(),
            title: 'Webプログラミング 第1回資料',
            description: '講義資料です。',
            file: $this->file(),
            publishDate: null,
        );
    }

    private function file(): UploadFile
    {
        return new UploadFile(
            originalName: 'test.pdf',
            mimeType: 'application/pdf',
            size: 100,
            contents: 'test content',
        );
    }
}
