<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Material\Entities\Material;
use DateTimeImmutable;

trait MaterialTestHelper
{
    use IdTestHelper;

    protected function materialTitle(?string $title = null): string
    {
        return $title ?? 'テストタイトル';
    }

    protected function materialDescription(?string $description = null): string
    {
        return $description ?? 'テスト説明';
    }

    protected function materialFilePath(?string $filePath = null): string
    {
        return $filePath ?? 'materials/test.pdf';
    }

    protected function materialPublishDate(?DateTimeImmutable $publishDate = null): ?DateTimeImmutable
    {
        return $publishDate ?? new DateTimeImmutable('2024-01-01 12:00:00');
    }

    protected function createMaterial(
        ?int $courseOfferingId = null,
        ?string $title = null,
        ?string $description = null,
        ?string $filePath = null,
        ?DateTimeImmutable $publishDate = null,
    ): Material {
        return Material::create(
            courseOfferingId: $this->courseOfferingId($courseOfferingId),
            title: $title ?? $this->materialTitle(),
            description: $description ?? $this->materialDescription(),
            filePath: $filePath,
            publishDate: $publishDate,
        );
    }

    protected function reconstructMaterial(
        ?int $id = null,
        ?int $courseOfferingId = null,
        ?string $title = null,
        ?string $description = null,
        ?string $filePath = null,
        ?DateTimeImmutable $publishDate = null,
    ): Material {
        return Material::reconstruct(
            id: $this->materialId($id),
            courseOfferingId: $this->courseOfferingId($courseOfferingId),
            title: $title ?? $this->materialTitle(),
            description: $description ?? $this->materialDescription(),
            filePath: $filePath,
            publishDate: $publishDate,
        );
    }
}
