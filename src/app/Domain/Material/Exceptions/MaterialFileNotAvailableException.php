<?php

namespace App\Domain\Material\Exceptions;

use App\Domain\Exceptions\DomainException;

final class MaterialFileNotAvailableException extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = 'ファイルが利用できません。';

    public function __construct()
    {
        parent::__construct('Material file is not available.');
    }
}
