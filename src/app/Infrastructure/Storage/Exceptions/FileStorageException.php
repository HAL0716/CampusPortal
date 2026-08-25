<?php

namespace App\Infrastructure\Storage\Exceptions;

use App\Infrastructure\Exceptions\InfrastructureException;

final class FileStorageException extends InfrastructureException
{
    protected const DEFAULT_USER_MESSAGE = 'ファイルの操作に失敗しました。';

    public function __construct()
    {
        parent::__construct('File storage error');
    }
}
