<?php

namespace Tests\Unit\Domain\Material;

use App\Domain\Material\Exceptions\MaterialFileNotAvailableException;
use App\Domain\Material\Exceptions\MaterialIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\MaterialTestHelper;

final class MaterialTest extends TestCase
{
    use MaterialTestHelper;

    public function test_create_returns_material_without_id(): void
    {
        $material = $this->createMaterial(
            description: $this->materialDescription(),
            filePath: $this->materialFilePath(),
            publishDate: $this->materialPublishDate(),
        );

        $this->assertNull($material->id());
        $this->assertSame($this->courseOfferingId()->value(), $material->courseOfferingId()->value());
        $this->assertSame($this->materialTitle(), $material->title());
        $this->assertSame($this->materialDescription(), $material->description());
        $this->assertSame($this->materialFilePath(), $material->filePath());
        $this->assertEquals($this->materialPublishDate(), $material->publishDate());
    }

    public function test_reconstruct_restores_material_with_id(): void
    {
        $material = $this->reconstructMaterial(
            description: $this->materialDescription(),
            filePath: $this->materialFilePath(),
            publishDate: $this->materialPublishDate(),
        );

        $this->assertSame($this->materialId()->value(), $material->id()->value());
        $this->assertSame($this->courseOfferingId()->value(), $material->courseOfferingId()->value());
        $this->assertSame($this->materialTitle(), $material->title());
        $this->assertEquals($this->materialPublishDate(), $material->publishDate());
    }

    public function test_require_id_returns_assigned_id(): void
    {
        $material = $this->reconstructMaterial();

        $this->assertSame($this->materialId()->value(), $material->requireId()->value());
    }

    public function test_require_id_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(MaterialIdNotAssignedException::class);

        $this->createMaterial()->requireId();
    }

    public function test_require_file_path_returns_file_path_when_available(): void
    {
        $material = $this->createMaterial(filePath: 'materials/test.pdf');

        $this->assertSame('materials/test.pdf', $material->requireFilePath());
    }

    public function test_require_file_path_throws_exception_when_file_is_not_available(): void
    {
        $this->expectException(MaterialFileNotAvailableException::class);

        $this->createMaterial()->requireFilePath();
    }
}
