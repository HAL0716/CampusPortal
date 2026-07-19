<?php

namespace App\Application\Database;

use Illuminate\Database\QueryException;
use UnitEnum;

interface DuplicateDetectorInterface
{
    public function isDuplicate(QueryException $e, UnitEnum ...$targets): bool;
}
