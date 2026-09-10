<?php

namespace App\Domain\Student\Exceptions;

use App\Domain\Exceptions\InvalidStatusException;
use App\Domain\Student\Enums\StudentStatus;

final class InvalidStatusTransition extends InvalidStatusException
{
    public function __construct(StudentStatus $old, StudentStatus $new)
    {
        parent::__construct("Invalid transition from {$old->value} to {$new->value}.");
    }
}
