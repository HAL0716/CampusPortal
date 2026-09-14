<?php

namespace App\Domain\Semester\Exceptions;

use App\Domain\Exceptions\DomainException;

final class InvalidAcademicYear extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = '学年が不正な形式です。';

    public function __construct(string $academicYear)
    {
        parent::__construct("Invalid academic year: {$academicYear}.");
    }
}
