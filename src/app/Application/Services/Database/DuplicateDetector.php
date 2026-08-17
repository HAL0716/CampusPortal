<?php

namespace App\Application\Services\Database;

use Illuminate\Database\QueryException;
use UnitEnum;

interface DuplicateDetector
{
    public function isDuplicate(QueryException $e, UnitEnum ...$targets): bool;
}
