<?php

namespace App\Infrastructure\Storage\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class FileStorageException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('File storage error');
    }

    public function userMessage(): string
    {
        return 'ストレージへの保存に失敗しました。';
    }
}
