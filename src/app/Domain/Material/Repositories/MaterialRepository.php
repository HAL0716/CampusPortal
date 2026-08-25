<?php

namespace App\Domain\Material\Repositories;

use App\Domain\Material\Entities\Material;
use App\Domain\Material\ValueObjects\MaterialId;

interface MaterialRepository
{
    public function save(Material $material): Material;

    public function getById(MaterialId $id): Material;
}
