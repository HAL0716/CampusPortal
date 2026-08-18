<?php

namespace Tests\Unit\Domain\Material;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Material\Entities\Material;
use App\Domain\Material\ValueObjects\MaterialId;
use PHPUnit\Framework\TestCase;

final class MaterialTest extends TestCase
{
    public function test_can_create_material(): void
    {
        $material = Material::create(new CourseOfferingId(1), '資料タイトル', null, null, null);

        self::assertNull($material->id());
        self::assertSame(1, $material->courseOfferingId()->value());
        self::assertSame('資料タイトル', $material->title());
    }

    public function test_can_reconstruct_material(): void
    {
        $material = Material::reconstruct(new MaterialId(1), new CourseOfferingId(1), '資料タイトル', null, null, null);

        self::assertSame(1, $material->id()->value());
        self::assertSame(1, $material->courseOfferingId()->value());
        self::assertSame('資料タイトル', $material->title());
    }
}
