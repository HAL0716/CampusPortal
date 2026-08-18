<?php

namespace App\Domain\Material\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class MaterialNotFoundException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Material not found.');
    }

    public function userMessage(): string
    {
        return '資料がありません。';
    }
}
