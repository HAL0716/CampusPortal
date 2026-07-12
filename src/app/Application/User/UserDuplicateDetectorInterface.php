<?php

namespace App\Application\User;

use Illuminate\Database\QueryException;

interface UserDuplicateDetectorInterface
{
    public function isDuplicate(QueryException $e): bool;
}
