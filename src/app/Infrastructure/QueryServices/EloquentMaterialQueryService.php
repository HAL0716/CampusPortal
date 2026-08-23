<?php

namespace App\Infrastructure\QueryServices;

use App\Application\Contexts\Material\DTOs\MaterialDetailDTO;
use App\Application\Contexts\Material\Services\MaterialQueryService;
use App\Application\Services\Clock\Clock;
use App\Domain\Material\Exceptions\MaterialNotFoundException;
use App\Domain\Material\ValueObjects\MaterialId;
use App\Models\Material as MaterialModel;

final readonly class EloquentMaterialQueryService implements MaterialQueryService
{
    public function __construct(
        private Clock $clock,
    ) {}

    public function getDetail(MaterialId $id): MaterialDetailDTO
    {
        $material = MaterialModel::query()
            ->whereKey($id->value())
            ->publishedAt($this->clock->now())
            ->first();

        if ($material === null) {
            throw new MaterialNotFoundException;
        }

        return new MaterialDetailDTO(
            id: $material->id,
            title: $material->title,
            description: $material->description,
            filePath: $material->file_path,
        );
    }
}
