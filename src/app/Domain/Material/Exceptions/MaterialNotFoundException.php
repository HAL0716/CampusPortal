<?php

namespace App\Domain\Material\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class MaterialNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = '資料情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Material not found.');
    }
}
