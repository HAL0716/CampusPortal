<?php

namespace App\Application\Contexts\FinalGrade\DTOs;

use App\Domain\FinalGrade\Enums\FinalGradeType;

final readonly class EnrollmentForFinalGradeDTO
{
    public function __construct(
        public int $enrollmentId,
        public string $studentNumber,
        public ?FinalGradeType $finalGrade,
    ) {}
}
