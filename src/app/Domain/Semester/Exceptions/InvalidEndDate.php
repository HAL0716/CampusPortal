<?php

namespace App\Domain\Semester\Exceptions;

use App\Domain\Exceptions\DomainException;
use DateTimeImmutable;

final class InvalidEndDate extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = '終了日が不正な形式です。';

    public function __construct(DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        parent::__construct("Invalid end date: {$endDate->format('Y-m-d')} for start date: {$startDate->format('Y-m-d')}.");
    }
}
