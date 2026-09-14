<?php

namespace App\Domain\Student\Exceptions;

use App\Domain\Exceptions\DomainException;

final class InsufficientCredits extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = '単位数が不足しています。';

    public function __construct(
        public int $required,
        public int $actual,
    ) {
        parent::__construct("Insufficient credits: required {$required}, actual {$actual}.");
    }
}
