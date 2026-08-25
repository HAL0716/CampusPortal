<?php

namespace Tests\Unit\Infrastructure\Storage;

use App\Application\Services\Storage\UploadFile;
use App\Infrastructure\Storage\Exceptions\FileStorageException;
use App\Infrastructure\Storage\LaravelFileStorage;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class LaravelFileStorageTest extends TestCase
{
    private LaravelFileStorage $storage;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        config(['filesystems.default' => 'local']);

        $this->storage = new LaravelFileStorage;
    }

    public function test_can_store_file(): void
    {
        $path = $this->storage->store($this->file(), 'materials');

        $this->assertMatchesRegularExpression('#^materials/[a-f0-9-]+\.pdf$#', $path);

        Storage::assertExists($path);
    }

    public function test_can_check_file_exists(): void
    {
        Storage::disk('local')->put('materials/test.pdf', 'contents');

        $this->assertTrue($this->storage->exists('materials/test.pdf'));
    }

    public function test_cannot_check_missing_file_as_existing(): void
    {
        $this->assertFalse($this->storage->exists('materials/test.pdf'));
    }

    public function test_can_delete_file(): void
    {
        Storage::disk('local')->put('materials/test.pdf', 'contents');

        $this->storage->delete('materials/test.pdf');

        Storage::assertMissing('materials/test.pdf');
    }

    public function test_cannot_delete_missing_file(): void
    {
        $this->expectException(FileStorageException::class);

        $this->storage->delete('materials/test.pdf');
    }

    public function test_can_resolve_download(): void
    {
        Storage::disk('local')->put('materials/test.pdf', 'contents');

        $download = $this->storage->resolveDownload(
            'materials/test.pdf',
            'lecture.pdf',
        );

        $this->assertSame(Storage::disk('local')->path('materials/test.pdf'), $download->path);
        $this->assertSame('lecture.pdf', $download->fileName);
    }

    public function test_cannot_resolve_download_for_missing_file(): void
    {
        $this->expectException(FileStorageException::class);

        $this->storage->resolveDownload('materials/test.pdf', 'lecture.pdf');
    }

    private function file(): UploadFile
    {
        return new UploadFile(
            originalName: 'test.pdf',
            mimeType: 'application/pdf',
            size: 1024,
            contents: 'file contents',
        );
    }
}
