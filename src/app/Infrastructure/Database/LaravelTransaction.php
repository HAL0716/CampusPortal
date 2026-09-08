<?php

namespace App\Infrastructure\Database;

use App\Application\Services\Database\Transaction;
use Illuminate\Support\Facades\DB;

final class LaravelTransaction implements Transaction
{
    public function run(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
