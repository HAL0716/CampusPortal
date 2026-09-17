<?php

namespace App\Domain\CourseOffering\Exceptions;

use App\Domain\Exceptions\AlreadyExistsException;

final class CourseOfferingAlreadyExistsException extends AlreadyExistsException
{
    protected const DEFAULT_USER_MESSAGE = '開講は既に存在します。';

    public function __construct()
    {
        parent::__construct('Course offering already exists.');
    }
}
