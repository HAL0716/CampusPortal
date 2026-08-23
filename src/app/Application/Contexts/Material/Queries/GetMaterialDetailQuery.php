<?php

namespace App\Application\Contexts\Material\Queries;

use App\Domain\Material\ValueObjects\MaterialId;

final readonly class GetMaterialDetailQuery
{
    public function __construct(
        public MaterialId $materialId,
    ) {}
}
