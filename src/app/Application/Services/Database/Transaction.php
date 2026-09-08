<?php

namespace App\Application\Services\Database;

interface Transaction
{
    public function run(callable $callback): mixed;
}
