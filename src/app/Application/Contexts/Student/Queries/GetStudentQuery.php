<?php

namespace App\Application\Contexts\Student\Queries;

use App\Domain\Student\ValueObjects\StudentId;

final readonly class GetStudentQuery
{
    public function __construct(
        public StudentId $studentId,
    ) {}
}
