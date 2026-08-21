<?php

namespace App\Domain\CourseOffering\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class CourseOfferingNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = '開講情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Course offering not found.');
    }
}
