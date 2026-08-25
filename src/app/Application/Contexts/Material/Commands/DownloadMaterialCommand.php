<?php

namespace App\Application\Contexts\Material\Commands;

use App\Domain\Material\ValueObjects\MaterialId;

final readonly class DownloadMaterialCommand
{
    public function __construct(
        public MaterialId $materialId,
    ) {}
}
