<?php

namespace App\Domain\Semester\Entities;

use App\Domain\Academic\Term;
use App\Domain\Semester\Exceptions\SemesterIdNotAssignedException;
use App\Domain\Semester\SemesterId;

final readonly class Semester
{
    private function __construct(
        private ?SemesterId $id,
        private string $academicYear,
        private Term $term,
    ) {}

    public static function create(string $academicYear, Term $term): self
    {
        return new self(null, $academicYear, $term);
    }

    public static function reconstruct(SemesterId $id, string $academicYear, Term $term): self
    {
        return new self($id, $academicYear, $term);
    }

    public function id(): ?SemesterId
    {
        return $this->id;
    }

    public function requireId(): SemesterId
    {
        if ($this->id === null) {
            throw new SemesterIdNotAssignedException;
        }

        return $this->id;
    }

    public function academicYear(): string
    {
        return $this->academicYear;
    }

    public function term(): Term
    {
        return $this->term;
    }
}
