<?php

namespace App\Application\Contexts\Semester\Queries;

use App\Domain\Semester\ValueObjects\SemesterId;

final readonly class GetSemesterQuery
{
    public function __construct(
        public SemesterId $semesterId,
    ) {}
}
