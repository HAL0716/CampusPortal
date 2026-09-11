<?php

namespace App\Application\Contexts\Student\Commands;

use App\Domain\Student\Enums\StudentStatus;
use App\Domain\Student\ValueObjects\StudentId;

final readonly class UpdateStudentStatusCommand
{
    public function __construct(
        public StudentId $studentId,
        public StudentStatus $status,
        public int $credits,
    ) {}
}
