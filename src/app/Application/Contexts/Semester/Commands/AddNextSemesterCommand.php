<?php

namespace App\Application\Contexts\Semester\Commands;

use DateTimeImmutable;

final readonly class AddNextSemesterCommand
{
    public function __construct(
        public DateTimeImmutable $endDate,
    ) {}
}
