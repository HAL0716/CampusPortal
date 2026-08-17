<?php

namespace App\Domain\Material\Repositories;

use App\Domain\Material\Entities\Material;

interface MaterialRepository
{
    public function save(Material $material): Material;
}
