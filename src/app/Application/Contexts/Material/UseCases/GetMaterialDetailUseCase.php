<?php

namespace App\Application\Contexts\Material\UseCases;

use App\Application\Contexts\Material\DTOs\MaterialDetailDTO;
use App\Application\Contexts\Material\Queries\GetMaterialDetailQuery;
use App\Application\Contexts\Material\Services\MaterialQueryService;

final readonly class GetMaterialDetailUseCase
{
    public function __construct(
        private MaterialQueryService $materialQueryService,
    ) {}

    public function execute(GetMaterialDetailQuery $query): MaterialDetailDTO
    {
        return $this->materialQueryService->getDetail($query->materialId);
    }
}
