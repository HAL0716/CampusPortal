<?php

namespace App\Application\Contexts\Material\Services;

use App\Application\Contexts\Material\DTOs\MaterialDetailDTO;
use App\Domain\Material\ValueObjects\MaterialId;

interface MaterialQueryService
{
    public function getDetail(MaterialId $id): MaterialDetailDTO;
}
