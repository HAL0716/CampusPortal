<?php

namespace App\Domain\FinalGrade\Entities;

use App\Domain\Enrollment\EnrollmentId;
use App\Domain\FinalGrade\Exceptions\FinalGradeIdNotAssignedException;
use App\Domain\FinalGrade\FinalGradeId;
use App\Domain\FinalGrade\FinalGradeType;

final readonly class FinalGrade
{
    private function __construct(
        private ?FinalGradeId $id,
        private EnrollmentId $enrollmentId,
        private FinalGradeType $grade,
    ) {}

    public static function create(EnrollmentId $enrollmentId, FinalGradeType $grade): self
    {
        return new self(null, $enrollmentId, $grade);
    }

    public static function reconstruct(FinalGradeId $id, EnrollmentId $enrollmentId, FinalGradeType $grade): self
    {
        return new self($id, $enrollmentId, $grade);
    }

    public function id(): ?FinalGradeId
    {
        return $this->id;
    }

    public function requireId(): FinalGradeId
    {
        if ($this->id === null) {
            throw new FinalGradeIdNotAssignedException;
        }

        return $this->id;
    }

    public function enrollmentId(): EnrollmentId
    {
        return $this->enrollmentId;
    }

    public function grade(): FinalGradeType
    {
        return $this->grade;
    }
}
